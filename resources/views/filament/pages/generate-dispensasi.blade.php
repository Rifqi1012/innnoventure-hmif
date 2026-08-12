<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div id="surat-preview" class="lg:col-span-8">
            <x-filament::section heading="Live Preview Surat" description="Preview diperbarui otomatis mengikuti isi form.">
                <div
                    class="overflow-hidden rounded-xl bg-gray-200 p-3 dark:bg-gray-800 sm:p-6"
                    x-data="{
                        scale: 1,
                        resizeObserver: null,
                        resizePreview() {
                            this.scale = Math.min(this.$refs.viewport.getBoundingClientRect().width / 794, 1)
                        },
                    }"
                    x-init="$nextTick(() => {
                        resizePreview()
                        resizeObserver = new ResizeObserver(() => resizePreview())
                        resizeObserver.observe($refs.viewport)
                    })"
                >
                    <div
                        x-ref="viewport"
                        class="mx-auto w-full max-w-[794px] overflow-hidden"
                        x-bind:style="`height: ${1123 * scale}px`"
                    >
                        <div
                            class="h-[1123px] w-[794px] origin-top-left bg-white shadow-xl"
                            x-bind:style="`width: 794px; height: 1123px; transform-origin: top left; transform: scale(${scale})`"
                        >
                            @include('surat.dispensasi-document', ['data' => $this->previewData, 'preview' => true])
                        </div>
                    </div>
                </div>
            </x-filament::section>
        </div>

        <div class="lg:col-span-4">
            <form wire:submit="generatePdf" class="space-y-6">
                {{ $this->form }}

                <div class="sticky bottom-4 flex flex-wrap justify-end gap-3 rounded-xl bg-white p-4 shadow-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <x-filament::button type="button" color="gray" icon="heroicon-o-eye"
                        x-on:click="document.getElementById('surat-preview').scrollIntoView({ behavior: 'smooth' })">
                        Preview
                    </x-filament::button>
                    <x-filament::button type="submit" icon="heroicon-o-arrow-down-tray" wire:loading.attr="disabled">
                        Generate PDF
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
