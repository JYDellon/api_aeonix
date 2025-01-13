import { startStimulusApp } from '@symfony/stimulus-bridge';

// Importer automatiquement tous les contrôleurs Stimulus
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.(j|t)sx?$/
));
