import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';
import './bootstrap';
import { destroyAllMotion, initMotion, registerLivewireMotionHooks } from './motion/index';

document.addEventListener('livewire:navigated', () => {
    destroyAllMotion();
    initMotion();
});

registerLivewireMotionHooks(Livewire);
Livewire.start();
