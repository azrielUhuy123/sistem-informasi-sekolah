import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';

const statusPath = new URL('../../resources/views/ppdb/public/status.blade.php', import.meta.url);
const statusSource = readFileSync(statusPath, 'utf8');
const firstStageMarker = statusSource.indexOf('data-progress-stage="pengajuan"');
const progressStart = firstStageMarker >= 0 ? statusSource.lastIndexOf('<div', firstStageMarker) : -1;
const progressEnd = progressStart >= 0 ? statusSource.indexOf('@endisset', progressStart) : -1;
const progressMarkup = progressStart >= 0
    ? statusSource.slice(progressStart, progressEnd >= 0 ? progressEnd : undefined)
    : '';

const stageMarkers = [...progressMarkup.matchAll(/data-progress-stage=["']([^"']+)["']/g)];

function getStageMarkup(stageName) {
    const stageIndex = stageMarkers.findIndex(([, name]) => name === stageName);
    if (stageIndex < 0) return '';

    const start = progressMarkup.lastIndexOf('<div', stageMarkers[stageIndex].index);
    const end = stageMarkers[stageIndex + 1]?.index ?? progressMarkup.length;
    return progressMarkup.slice(start, end);
}

test('the progress contains exactly three process stages with the required labels', () => {
    assert.deepEqual(stageMarkers.map(([, name]) => name), ['pengajuan', 'keputusan', 'daftar-ulang']);
    for (const label of ['Pendaftaran', 'Keputusan Sekolah', 'Daftar Ulang']) {
        assert.ok(progressMarkup.includes(label), `progress should include the ${label} stage`);
    }
});

test('each process stage has an indicator and the stages have two connectors', () => {
    assert.equal((progressMarkup.match(/data-progress-indicator\b/g) || []).length, stageMarkers.length);
    assert.equal((progressMarkup.match(/data-progress-connector\b/g) || []).length, stageMarkers.length - 1);
    for (const [, stageName] of stageMarkers) {
        assert.ok(getStageMarkup(stageName).includes('data-progress-indicator'), `${stageName} should have an indicator`);
    }
});

test('the progress does not include the removed verification state', () => {
    assert.doesNotMatch(progressMarkup, /verifikasi/i);
});

test('the progress does not use a percentage bar', () => {
    assert.doesNotMatch(progressMarkup, /progress-bar|width\s*:\s*\d+%/i);
});

test('pending status makes Pengajuan the active process stage', () => {
    const applicationStage = getStageMarkup('pengajuan');
    const decisionStage = getStageMarkup('keputusan');
    const registrationStage = getStageMarkup('daftar-ulang');

    assert.ok(applicationStage.includes("$data->status === 'pending'"));
    assert.ok(applicationStage.includes('active'));
    assert.ok(applicationStage.includes('bi-circle-fill'));
    assert.ok(decisionStage.includes('pending'));
    assert.ok(decisionStage.includes('bi-circle'));
    assert.ok(registrationStage.includes('pending'));
    assert.ok(registrationStage.includes('bi-circle'));
});

test('accepted with daftar ulang belum makes Daftar Ulang the current stage', () => {
    const applicationStage = getStageMarkup('pengajuan');
    const decisionStage = getStageMarkup('keputusan');
    const registrationStage = getStageMarkup('daftar-ulang');

    assert.ok(registrationStage.includes("$data->status === 'diterima'"));
    assert.ok(registrationStage.includes("$data->status_daftar_ulang === 'belum'"));
    assert.ok(applicationStage.includes('completed'));
    assert.ok(applicationStage.includes('bi-check-circle-fill'));
    assert.ok(decisionStage.includes('completed'));
    assert.ok(decisionStage.includes('bi-check-circle-fill'));
    assert.ok(registrationStage.includes('active'));
    assert.ok(registrationStage.includes('bi-circle-fill'));
});

test('accepted with daftar ulang sudah completes all process stages', () => {
    const applicationStage = getStageMarkup('pengajuan');
    const decisionStage = getStageMarkup('keputusan');
    const registrationStage = getStageMarkup('daftar-ulang');

    assert.ok(registrationStage.includes("$data->status === 'diterima'"));
    assert.ok(registrationStage.includes("$data->status_daftar_ulang === 'sudah'"));
    for (const stage of [applicationStage, decisionStage, registrationStage]) {
        assert.ok(stage.includes('completed'));
        assert.ok(stage.includes('bi-check-circle-fill'));
    }
});

test('rejected status ends at Keputusan without an active Daftar Ulang stage', () => {
    const applicationStage = getStageMarkup('pengajuan');
    const decisionStage = getStageMarkup('keputusan');
    const registrationStage = getStageMarkup('daftar-ulang');

    assert.ok(decisionStage.includes("$data->status === 'ditolak'"));
    assert.ok(applicationStage.includes('completed'));
    assert.ok(applicationStage.includes('bi-check-circle-fill'));
    assert.ok(decisionStage.includes('rejected'));
    assert.ok(decisionStage.includes('bi-x-circle-fill'));
    assert.ok(registrationStage.includes('pending'));
    assert.ok(registrationStage.includes('bi-circle'));

    const activeStateIndex = registrationStage.indexOf("'active'");
    assert.ok(activeStateIndex >= 0, 'Daftar Ulang should be active in its applicable state');
    assert.ok(registrationStage.indexOf("$data->status === 'diterima'") < activeStateIndex);
    assert.ok(registrationStage.indexOf("$data->status_daftar_ulang === 'belum'") < activeStateIndex);
    assert.ok(registrationStage.includes("'pending'"), 'Daftar Ulang should have a pending fallback for a rejected record');
});

test('progress indicators use Bootstrap Icons for completed, active, pending, and rejected states', () => {
    assert.match(progressMarkup, /bi-check-circle-fill/);
    assert.match(progressMarkup, /bi-circle-fill/);
    assert.match(progressMarkup, /bi-circle\b/);
    assert.match(progressMarkup, /bi-x-circle-fill/);
});