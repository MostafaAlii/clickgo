<?php
namespace App\Dtos;
use Illuminate\Http\UploadedFile;
class CountryDTO {
    public function __construct(
        public array $name,
        public array $description,
        public ? UploadedFile $countryImage = null,
    ) {}
    public static function fromRequest($request): self {
        return new self(
            name: $request->input('name'),
            description: $request->input('description'),
            countryImage: $request->file('country')
        );
    }
}
