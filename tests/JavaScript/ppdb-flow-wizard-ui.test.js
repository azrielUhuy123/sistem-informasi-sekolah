import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';

const flowPath = new URL('../../resources/views/ppdb/public/alur.blade.php', import.meta.url);
const flowSource = readFileSync(flowPath, 'utf8');
const wizardRootMatch = flowSource.match(/<div\b(?=[^>]*\bdata-ppdb-wizard\b)[^>]*>/);

function extractWizardMarkup(source, rootMatch) {
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

const wizardMarkup = extractWizardMarkup(flowSource, wizardRootMatch);
const stepMarkers = [...wizardMarkup.matchAll(/data-ppdb-wizard-step=["'](\d+)["']/g)];
const stepSections = stepMarkers.map((marker, index) => {
    const end = stepMarkers[index + 1]?.index ?? wizardMarkup.length;
    return wizardMarkup.slice(marker.index, end);
});

test('the guide wizard contains exactly eight ordered steps', () => {
    assert.deepEqual(
        stepMarkers.map((marker) => Number(marker[1])),
        [1, 2, 3, 4, 5, 6, 7, 8],
    );
});

test('all eight steps have indicators and content with titles', () => {
    assert.equal(stepSections.length, 8);
    stepSections.forEach((stepMarkup, index) => {
        assert.ok(/data-ppdb-step-indicator/.test(stepMarkup), `step ${index + 1} should have an indicator`);
        assert.ok(/data-ppdb-step-content/.test(stepMarkup), `step ${index + 1} should have content`);
        assert.ok(/data-ppdb-step-title/.test(stepMarkup), `step ${index + 1} should have a title`);
    });
    assert.ok(/preparation-list/.test(stepSections[0]), 'step 1 should contain the preparation checklist');
    stepSections.slice(1).forEach((stepMarkup, index) => {
        assert.ok(/data-ppdb-step-description/.test(stepMarkup), `step ${index + 2} should have a description`);
    });
});

test('media matches the current step structure', () => {
    assert.doesNotMatch(stepSections[0], /data-ppdb-image-placeholder/);
    for (const stepNumber of [2, 3, 4, 5, 7, 8]) {
        assert.ok(/data-ppdb-image-placeholder/.test(stepSections[stepNumber - 1]), `step ${stepNumber} should have its image area`);
    }
    assert.ok(/data-ppdb-video-placeholder/.test(stepSections[5]), 'step 6 should contain the video area');
    assert.doesNotMatch(stepSections[5], /data-ppdb-image-placeholder/);
});

test('navigation follows the current eight-step flow', () => {
    stepSections.forEach((stepMarkup, index) => {
        assert.ok(/data-ppdb-step-navigation/.test(stepMarkup), `step ${index + 1} should have navigation`);
    });
    stepSections.slice(0, 7).forEach((stepMarkup, index) => {
        assert.ok(/data-ppdb-wizard-next/.test(stepMarkup), `step ${index + 1} should advance`);
    });
    stepSections.slice(1).forEach((stepMarkup, index) => {
        assert.ok(/data-ppdb-wizard-previous/.test(stepMarkup), `step ${index + 2} should go back`);
    });
});

test('status indicators use the icon classes mapped by the wizard', () => {
    assert.match(flowSource, /complete\s*:\s*['"]bi-check-circle-fill['"]/);
    assert.match(flowSource, /active\s*:\s*['"]bi-circle-fill['"]/);
    assert.match(flowSource, /pending\s*:\s*['"]bi-circle['"]/);
});

test('indicator icons do not use border or circle-shape CSS', () => {
    const indicatorRules = [...flowSource.matchAll(/([^{}]*\.flow-wizard-indicator[^{}]*)\{([^{}]*)\}/g)]
        .map(([, selector, declarations]) => `${selector}{${declarations}}`)
        .join('\n');

    assert.doesNotMatch(indicatorRules, /\bborder(?:-[\w-]+)?\s*:/i);
    assert.doesNotMatch(indicatorRules, /border-radius\s*:\s*50%/i);
});

test('the wizard has seven connectors and no linear progress bar', () => {
    assert.equal((wizardMarkup.match(/data-ppdb-wizard-connector\b/g) || []).length, 7);
    assert.doesNotMatch(flowSource, /wizardBar|class=["'][^"']*\bprogress(?:-bar)?\b/i);
});

test('the video is in step 6 and the registration CTA is in step 8', () => {
    const finalStep = stepSections[7] || '';

    assert.ok(/data-ppdb-video-placeholder/.test(stepSections[5]));
    assert.ok(!/data-ppdb-video-placeholder/.test(finalStep));
    assert.match(finalStep, /data-bs-toggle=["']modal["']/);
    assert.match(finalStep, /data-bs-target=["']#ppdbWarningModal["']/);
    assert.match(finalStep, /Mulai Pendaftaran/);
});

test('the registration warning modal confirms before navigating to ppdb.create', () => {
    assert.ok(/id=["']ppdbWarningModal["']/.test(flowSource), 'the warning modal should be present');
    assert.ok(/id=["']ppdbWarningAgreement["']/.test(flowSource), 'the modal should contain its confirmation control');
    assert.ok(/id=["']ppdbContinueButton["']/.test(flowSource), 'the modal should contain a continue control');
    assert.match(flowSource, /data-form-url=["']\{\{\s*route\('ppdb\.create'\)\s*\}\}["']/);
});