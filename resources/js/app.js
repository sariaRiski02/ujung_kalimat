import Alpine from "alpinejs";
// Livewire already bundles and starts its own Alpine; starting a second one breaks reactivity.
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}
