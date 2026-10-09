import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';

const layoutPath = new URL('../../resources/views/layouts/public.blade.php', import.meta.url);
const informationPath = new URL('../../resources/views/ppdb/public/informasi.blade.php', import.meta.url);
const statusPath = new URL('../../resources/views/ppdb/public/status.blade.php', import.meta.url);
const layoutSource = readFileSync(layoutPath, 'utf8');
const informationSource = readFileSync(informationPath, 'utf8');
const statusSource = readFileSync(statusPath, 'utf8');

const modalId = 'ppdbStatusModal';
const modalRootMatch = layoutSource.match(new RegExp(`<div\\b(?=[^>]*id=["']${modalId}["'])[^>]*>`));
const modalRoot = modalRootMatch?.[0] || '';

function extractModalMarkup(source, rootMatch) {
    if (!rootMatch) return '';

    const divTags = /<\/?div\b[^>]*>/gi;
    divTags.lastIndex = rootMatch.index;
    let depth = 0;
    let match;

    while ((match = divTags.exec(source))) {
        if (match[0].startsWith('</')) {
            depth -= 1;
            if (depth === 0) return source.slice(rootMatch.index, divTags.lastIndex);
        } else if (!match[0].endsWith('/>')) {
            depth += 1;
        }
    }

    return '';
}

const modalMarkup = extractModalMarkup(layoutSource, modalRootMatch);
const formStart = modalMarkup.indexOf('<form');
const formEnd = formStart >= 0 ? modalMarkup.indexOf('</form>', formStart) : -1;
const modalForm = formEnd >= 0 ? modalMarkup.slice(formStart, formEnd + '</form>'.length) : '';

test('the navbar Cek Status link triggers the Bootstrap modal without a duplicate Informasi trigger', () => {
    const trigger = layoutSource.match(/<a\b(?=[^>]*class=["'][^"']*\bnav-link\b)(?=[^>]*data-bs-toggle=["']modal["'])(?=[^>]*data-bs-target=["']#ppdbStatusModal["'])[^>]*>[\s\S]*?<\/a>/i);

    assert.ok(trigger, 'the public navbar should contain the Cek Status modal trigger');
    assert.match(trigger[0], /Cek Status/);
    assert.doesNotMatch(informationSource, /data-bs-target=["']#ppdbStatusModal["']/);
});

test('the status modal is defined in layouts.public with Bootstrap modal and fade classes', () => {
    assert.ok(modalMarkup, 'layouts.public should contain the status-check modal');
    assert.match(modalRoot, /class=["'][^"']*\bmodal\b[^"']*\bfade\b[^"']*["']/);
    assert.match(layoutSource, /data-bs-toggle=["']modal["'][^>]*data-bs-target=["']#ppdbStatusModal["']/);
});

test('the layout modal contains registration-number and birth-date inputs with a submit control', () => {
    assert.ok(modalMarkup, 'layouts.public should contain a dedicated status-check modal');
    assert.match(modalMarkup, /name=["']keyword["']/);
    assert.match(modalForm, /<button\b[^>]*type=["']submit["']/i);

    const fieldNames = [...modalForm.matchAll(/<(?:input|select|textarea)\b[^>]*\bname=["']([^"']+)["']/gi)]
        .map((match) => match[1])
        .filter((name) => name !== '_token');

    assert.deepEqual(fieldNames.sort(), ['keyword', 'tanggal_lahir']);
    assert.doesNotMatch(modalMarkup, /@isset\s*\(\s*\$data\s*\)|\$data->|data-status-result|<table\b/i);
});

test('the modal form POSTs to the existing status-check route with CSRF', () => {
    assert.match(modalForm, /<form\b[^>]*method=["']POST["']/i);
    assert.match(modalForm, /action=["']\{\{\s*route\('ppdb\.status\.cek'\)\s*\}\}["']/);
    assert.match(modalForm, /@csrf/);
});

test('validation errors can reopen the modal with the previous keyword', () => {
    assert.match(modalForm, /old\(\s*['"]keyword['"]\s*\)/);
    assert.match(modalForm, /@error\(\s*['"]keyword['"]\s*\)/);

    const validationRecovery = layoutSource.match(/@if\s*\(\s*\$errors->has\(\s*['"]keyword['"]\s*\)\s*\|\|\s*\$errors->has\(\s*['"]tanggal_lahir['"]\s*\)\s*\)([\s\S]*?)@endif/);
    assert.ok(validationRecovery, 'layouts.public should expose a keyword-validation recovery condition');
    assert.ok(validationRecovery[1].includes(modalId), 'the recovery condition should target the status modal');
});

test('status results remain in status.blade.php and outside the input modal', () => {
    assert.match(statusSource, /@isset\s*\(\s*\$data\s*\)/);
    assert.match(statusSource, /\$data->(?:nama|no_pendaftaran|status)/);
    assert.ok(modalMarkup, 'the layout should contain a separate input modal');
    assert.doesNotMatch(modalMarkup, /\$data->|data-status-result|<table\b/i);
});

test('the status result page does not contain a separate search form', () => {
    assert.doesNotMatch(statusSource, /<form\b[\s\S]*?name=["']keyword["']/i);
});

test('the existing viaLink condition remains available for signed status links', () => {
    assert.match(statusSource, /!isset\(\$viaLink\)\s*\|\|\s*!\$viaLink/);
});