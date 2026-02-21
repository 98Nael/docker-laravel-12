<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Models\Doctor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;


class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

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

                TextInput::make('specialization')
                    ->label('Specialization')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('image')
                    ->label('Profile Image')
                    ->image()
                    ->directory('doctors')
                    ->required(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('specialization')->searchable(),
                TextColumn::make('created_at')->dateTime(),
                ImageColumn::make('image')
                    ->label('Profile Image')
                    ->rounded()       // يجعل الصورة دائرية
                    ->square()        // أو استخدم square() أو حذف لتناسب شكل الجدول
                    ->disk('public'), // تأكد من أنك خزنت الصور في storage/app/public

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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }
}
