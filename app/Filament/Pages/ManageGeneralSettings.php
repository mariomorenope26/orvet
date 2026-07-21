<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageGeneralSettings extends Page
{
    protected string $view = 'filament.pages.manage-general-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Ajustes generales';

    protected static ?string $title = 'Ajustes generales del sitio';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::current()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('Ajustes')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Identidad')
                            ->schema([
                                Section::make()->columns(2)->schema([
                                    TextInput::make('site_name')->label('Nombre del sitio'),
                                    TextInput::make('tagline')->label('Eslogan'),
                                    FileUpload::make('logo')
                                        ->label('Logo')
                                        ->image()
                                        ->disk('public')
                                        ->directory('brand')
                                        ->columnSpanFull(),
                                    ColorPicker::make('primary_color')->label('Color principal'),
                                    Radio::make('checkout_mode')
                                        ->label('Modo del catálogo')
                                        ->options([
                                            'quote' => 'Solo catálogo + cotización por WhatsApp',
                                            'ecommerce' => 'Tienda con carrito y pago en línea',
                                        ])
                                        ->default('quote'),
                                ]),
                            ]),
                        Tab::make('Contacto')
                            ->schema([
                                Section::make()->columns(2)->schema([
                                    TextInput::make('address')->label('Dirección')->columnSpanFull(),
                                    TextInput::make('phone_fixed')->label('Teléfono fijo'),
                                    TextInput::make('phone_mobile')->label('Celular'),
                                    TextInput::make('whatsapp')->label('WhatsApp (formato 51999...)'),
                                    TextInput::make('email')->label('Correo')->email(),
                                    TextInput::make('schedule_weekday')->label('Horario Lun-Vie'),
                                    TextInput::make('schedule_saturday')->label('Horario Sábado'),
                                    Textarea::make('map_embed')
                                        ->label('Código de mapa (iframe de Google Maps)')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ]),
                            ]),
                        Tab::make('Redes sociales')
                            ->schema([
                                Section::make()->columns(2)->schema([
                                    TextInput::make('facebook')->label('Facebook')->url(),
                                    TextInput::make('instagram')->label('Instagram')->url(),
                                    TextInput::make('twitter')->label('Twitter / X')->url(),
                                    TextInput::make('pinterest')->label('Pinterest')->url(),
                                ]),
                            ]),
                        Tab::make('Nosotros')
                            ->schema([
                                RichEditor::make('about_history')->label('¿Quiénes somos? (Historia)'),
                                RichEditor::make('mission')->label('Misión'),
                                RichEditor::make('vision')->label('Visión'),
                                RichEditor::make('company_values')->label('Valores'),
                            ]),
                        Tab::make('Footer y legales')
                            ->schema([
                                Textarea::make('footer_about')->label('Texto del footer')->rows(3),
                                RichEditor::make('privacy_policy')->label('Política de privacidad'),
                                RichEditor::make('terms')->label('Términos de servicio'),
                            ]),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar cambios')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        Setting::current()->update($this->form->getState());

        Notification::make()
            ->success()
            ->title('Ajustes guardados correctamente')
            ->send();
    }
}
