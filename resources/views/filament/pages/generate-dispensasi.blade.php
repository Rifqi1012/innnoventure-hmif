<x-filament-panels::page>
    <x-filament::card>
        <form wire:submit="generatePdf">
            {{ $this->form }}

            <div class="mt-6 flex justify-end">
                <x-filament::button type="submit" color="primary" icon="heroicon-o-arrow-down-tray">
                    Generate & Download PDF
                </x-filament::button>
            </div>
        </form>
    </x-filament::card>
</x-filament-panels::page>
