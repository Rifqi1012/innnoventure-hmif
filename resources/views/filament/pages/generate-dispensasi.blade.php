<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div id="surat-preview" class="lg:col-span-8">
            <x-filament::section heading="Live Preview Surat" description="Preview diperbarui otomatis mengikuti isi form.">
                <div class="overflow-auto rounded-xl bg-gray-200 p-3 dark:bg-gray-800 sm:p-6">
                    <div class="mx-auto w-[794px] max-w-none bg-white shadow-xl">
                        @include('surat.dispensasi-document', ['data' => $this->previewData, 'preview' => true])
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
