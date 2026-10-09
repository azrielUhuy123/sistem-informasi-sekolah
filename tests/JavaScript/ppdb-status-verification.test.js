import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';

const migrationPath = new URL('../../database/migrations/2026_06_06_212249_create_ppdb_pendaftars_table.php', import.meta.url);
const controllerPath = new URL('../../app/Http/Controllers/PPDB/PublicController.php', import.meta.url);
const layoutPath = new URL('../../resources/views/layouts/public.blade.php', import.meta.url);
const statusPath = new URL('../../resources/views/ppdb/public/status.blade.php', import.meta.url);

const migrationSource = readFileSync(migrationPath, 'utf8');
const controllerSource = readFileSync(controllerPath, 'utf8');
const layoutSource = readFileSync(layoutPath, 'utf8');
const statusSource = readFileSync(statusPath, 'utf8');

const birthDateField = 'tanggal_lahir';
const methodStart = controllerSource.indexOf('public function cekStatus(');
const methodEnd = controllerSource.indexOf('private function generateNoPendaftaran', methodStart);
const cekStatusSource = methodStart >= 0
    ? controllerSource.slice(methodStart, methodEnd >= 0 ? methodEnd : undefined)
    : '';
const validationBlock = cekStatusSource.match(/\$request->validate\(\s*\[([\s\S]*?)\]\s*\)/)?.[1] || '';
const queryBlock = cekStatusSource.match(/\$data\s*=\s*PpdbPendaftar::([\s\S]*?)->first\(\s*\)\s*;/)?.[0] || '';
const modalId = 'ppdbStatusModal';
const modalRootMatch = layoutSource.match(new RegExp(`<div\\b(?=[^>]*id=["']${modalId}["'])[^>]*>`));

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
const modalInputs = [...modalForm.matchAll(/<input\b[^>]*>/gi)].map(([input]) => input);
const statusNameCell = statusSource.match(/<th[^>]*>\s*Nama\s*<\/th>([\s\S]*?)<\/tr>/i)?.[1] || '';

test('the database birth-date field is tanggal_lahir and cekStatus accepts both identifiers', () => {
    assert.match(migrationSource, /\$table->date\(\s*['"]tanggal_lahir['"]\s*\)/);
    assert.match(validationBlock, /['"]keyword['"]\s*=>\s*['"][^'"]*required/);
    assert.match(validationBlock, new RegExp(`['"]${birthDateField}['"]\\s*=>\\s*['"][^'"]*required`));
});

test('both required inputs are validated before searching', () => {
    const queryPosition = cekStatusSource.indexOf('$data = PpdbPendaftar::');

    assert.ok(validationBlock, 'cekStatus should validate request input');
    assert.ok(queryPosition > cekStatusSource.indexOf('$request->validate('), 'validation should run before the lookup');
    assert.match(validationBlock, /['"]keyword['"]\s*=>\s*['"][^'"]*required/);
    assert.match(validationBlock, new RegExp(`['"]${birthDateField}['"]\\s*=>\\s*['"][^'"]*required`));
});

test('the same pendaftar lookup matches registration number and tanggal_lahir', () => {
    assert.ok(queryBlock, 'cekStatus should contain a pendaftar lookup');
    assert.match(queryBlock, /no_pendaftaran/);
    assert.match(queryBlock, new RegExp(birthDateField));
    assert.match(queryBlock, /no_pendaftaran[\s\S]*tanggal_lahir|tanggal_lahir[\s\S]*no_pendaftaran/);
});

test('the lookup cannot query by registration number alone', () => {
    assert.ok(queryBlock, 'cekStatus should contain a pendaftar lookup');
    assert.match(queryBlock, /tanggal_lahir/, 'the lookup must include date of birth as a search condition');
});

test('a mismatched combination returns one generic error message', () => {
    const mismatchBranch = cekStatusSource.match(/if\s*\(!\$data\)\s*\{([\s\S]*?)\}/)?.[1] || '';

    assert.match(mismatchBranch, /Nomor pendaftaran atau tanggal lahir tidak sesuai\./);
    assert.doesNotMatch(mismatchBranch, /['"]Nomor pendaftaran tidak ditemukan\.['"]/i);
    assert.doesNotMatch(mismatchBranch, /['"]Tanggal lahir tidak sesuai\.['"]/i);
});

test('a matching combination renders the status page with $data', () => {
    assert.match(cekStatusSource, /view\(\s*['"]ppdb\.public\.status['"][\s\S]*?['"]data['"]\s*=>\s*\$data/);
});

test('the status modal POSTs keyword and tanggal_lahir with CSRF', () => {
    assert.match(modalForm, /<form\b[^>]*method=["']POST["']/i);
    assert.match(modalForm, /action=["']\{\{\s*route\('ppdb\.status\.cek'\)\s*\}\}["']/);
    assert.match(modalForm, /@csrf/);
    assert.ok(modalInputs.some((input) => /name=["']keyword["']/.test(input)));
    assert.ok(modalInputs.some((input) => /name=["']tanggal_lahir["']/.test(input)));
});

test('status.blade.php remains the result page and does not print the full name directly', () => {
    assert.match(statusSource, /@isset\s*\(\s*\$data\s*\)/);
    assert.match(statusSource, /\$data->(?:no_pendaftaran|status)/);
    assert.ok(statusNameCell, 'the status result should retain its name field area');
    assert.doesNotMatch(statusNameCell, /\{\{\s*\$data->nama\s*\}\}/);
    assert.match(statusNameCell, /\{\{[\s\S]*?\}\}/, 'the displayed name should use a derived/masked value');
});