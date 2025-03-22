<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\Pemasok;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = DB::table('kategori')->inRandomOrder()->select('id')->first();
        $data = DB::table('pemasok')->inRandomOrder()->select('nama_pemasok')->first();

        return [
            'nama' => fake()->randomElement(['Daster', 'Kameja', 'Sweater']),
            'kategori_id' => fake()->randomElement(Kategori::select('id')->get()),
            'pemasok_id' => fake()->randomElement(Pemasok::select('id')->get()),
            'harga' => fake()->numberBetween(1000, 10000),
            'stok' => fake()->numberBetween(1, 100),
            'size' => fake()->randomElement(['L', 'M', 'S'])

        ];
    }
}
