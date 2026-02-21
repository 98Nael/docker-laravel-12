<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;


class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Phone')
                    ->required()
                    ->tel()
                    ->maxLength(20),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('age')
                    ->label('Age')
                    ->required()
                    ->numeric()
                    ->minValue(0),

                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ])
                    ->required(),

                FileUpload::make('image')
                    ->label('Profile Image')
                    ->image()
                    ->directory('patients')
                    ->required(false)
                    ->disk('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                ImageColumn::make('image_url')
                    ->label('Profile Image')
                    ->rounded()
                    ->square()
                    ->disk('public'),

                TextColumn::make('name')->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('age')->sortable(),
                TextColumn::make('gender')->sortable(),
                TextColumn::make('created_at')->dateTime(),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
