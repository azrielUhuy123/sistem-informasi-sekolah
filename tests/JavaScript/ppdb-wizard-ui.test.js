import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';

const formPath = new URL('../../resources/views/ppdb/public/ppdb/form.blade.php', import.meta.url);
const formSource = readFileSync(formPath, 'utf8');
const wizardStart = formSource.indexOf('class="wizard-steps"');
const wizardEnd = formSource.indexOf('id="validationSummary"', wizardStart);
const wizardMarkup = formSource.slice(wizardStart, wizardEnd);
const stepMarkers = [...wizardMarkup.matchAll(/data-wizard-step=["'](\d+)["']/g)];
const stepSections = stepMarkers.map((marker, index) => {
    const end = stepMarkers[index + 1]?.index ?? wizardMarkup.length;
    return wizardMarkup.slice(marker.index, end);
});

test('wizard and form title are contained in the same card before the form', () => {
    const cardContent = formSource.match(/<div class="card shadow">([\s\S]*?<\/form>)/)?.[1];

    assert.ok(cardContent, 'the form should be inside the wizard card');
    assert.ok(cardContent.includes('Form Pendaftaran PPDB'), 'the card should contain the form title');
    assert.ok(cardContent.indexOf('wizard-steps') < cardContent.indexOf('<form'));
});

test('the wizard contains six ordered step elements', () => {
    assert.deepEqual(
        stepMarkers.map((marker) => Number(marker[1])),
        [1, 2, 3, 4, 5, 6],
    );
});

test('each step has a dedicated circular status indicator', () => {
    assert.equal(stepSections.length, 6);
    stepSections.forEach((stepMarkup, index) => {
        assert.ok(/data-wizard-indicator/.test(stepMarkup), `step ${index + 1} should have an indicator`);
    });
    assert.ok(/\.wizard-indicator\s*\{[^}]*border-radius:\s*50%/s.test(formSource), 'the indicator should be circular');
});

test('complete, active, and pending indicators use the required symbols', () => {
    assert.ok(/\.wizard-indicator\[data-status="complete"\][^{]*\{[^}]*content:\s*["']\\2713["']/s.test(formSource), 'complete indicator should show a check mark');
    assert.ok(/\.wizard-indicator\[data-status="active"\][^{]*\{[^}]*content:\s*["']\\25CF["']/s.test(formSource), 'active indicator should show a filled circle');
    assert.ok(/\.wizard-indicator\[data-status="pending"\][^{]*\{[^}]*content:\s*["']\\25CB["']/s.test(formSource), 'pending indicator should show an empty circle');
});

test('five connectors support completed and pending states', () => {
    assert.equal((wizardMarkup.match(/data-wizard-connector/g) || []).length, 5);
    assert.ok(/\.wizard-connector\[data-status="complete"\]/.test(formSource), 'completed connectors should have a style');
    assert.ok(/\.wizard-connector\[data-status="pending"\]/.test(formSource), 'pending connectors should have a style');
});

test('step labels remain available and no linear progress bar is present', () => {
    for (let step = 1; step <= 6; step += 1) {
        assert.ok(new RegExp(`id="labelStep${step}"`).test(formSource), `step ${step} label should remain available`);
    }

    assert.doesNotMatch(formSource, /wizardBar|class=["'][^"']*\bprogress(?:-bar)?\b/i);
});