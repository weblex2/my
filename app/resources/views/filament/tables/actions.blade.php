<div class="flex items-center space-x-2">
    {!! \Filament\Tables\Actions\ActionGroup::make([
        \Filament\Tables\Actions\DeleteAction::make(),
        \Filament\Tables\Actions\ViewAction::make(),
        \Filament\Tables\Actions\EditAction::make(),
    ])->record($record)->render() !!}
</div>
