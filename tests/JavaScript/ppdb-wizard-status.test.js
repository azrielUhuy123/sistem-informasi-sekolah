import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { runInNewContext } from 'node:vm';
import test from 'node:test';

const formPath = new URL('../../resources/views/ppdb/public/ppdb/form.blade.php', import.meta.url);
const formSource = readFileSync(formPath, 'utf8');
const formScript = [...formSource.matchAll(/<script>([\s\S]*?)<\/script>/g)]
    .map((match) => match[1])
    .find((script) => script.includes('function showStep(step)'));

const stepNames = [
    'Data Pribadi',
    'Data Alamat',
    'Asal Sekolah',
    'Data Orang Tua/Wali',
    'Kontak',
    'Data Periodik & Dokumen',
];

function makeErrorsByStep() {
    return Object.fromEntries(stepNames.map((stepName) => [stepName, []]));
}

function getStatuses(activeStep, errorsByStep) {
    const context = {
        document: { addEventListener() {} },
        window: {},
    };

    runInNewContext(formScript, context);
    assert.equal(
        typeof context.getWizardStepStatuses,
        'function',
        'form script should expose getWizardStepStatuses(activeStep, errorsByStep)',
    );

    return context.getWizardStepStatuses(activeStep, errorsByStep);
}

test('the currently open step has active status', () => {
    const errorsByStep = makeErrorsByStep();
    errorsByStep['Asal Sekolah'].push('Nama sekolah asal wajib diisi.');

    assert.equal(getStatuses(3, errorsByStep)['Asal Sekolah'], 'active');
});

test('a step with all required fields valid has complete status', () => {
    const errorsByStep = makeErrorsByStep();

    assert.equal(getStatuses(1, errorsByStep)['Data Alamat'], 'complete');
});

test('a step with a missing or invalid required field has pending status', () => {
    const errorsByStep = makeErrorsByStep();
    errorsByStep['Data Alamat'].push('RT harus berupa 1 sampai 3 digit angka.');

    assert.equal(getStatuses(1, errorsByStep)['Data Alamat'], 'pending');
});

test('a pending step does not change another step status', () => {
    const errorsByStep = makeErrorsByStep();
    errorsByStep['Data Alamat'].push('Alamat lengkap wajib diisi.');

    const statuses = getStatuses(1, errorsByStep);

    assert.equal(statuses['Data Alamat'], 'pending');
    assert.equal(statuses['Asal Sekolah'], 'complete');
});

test('all six steps have independently derived statuses', () => {
    const errorsByStep = makeErrorsByStep();
    errorsByStep['Data Alamat'].push('Kabupaten/Kota wajib diisi.');
    errorsByStep['Kontak'].push('Format email tidak valid.');

    const statuses = getStatuses(3, errorsByStep);

    assert.deepEqual(
        stepNames.map((stepName) => statuses[stepName]),
        ['complete', 'pending', 'active', 'complete', 'pending', 'complete'],
    );
});