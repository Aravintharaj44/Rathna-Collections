<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WomenCategorySeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            'Women' => [
                'is_featured' => true,
                'children' => [
                    'Inners' => [
                        'children' => [
                            'Bra' => [
                                'children' => [
                                    'Sports Bra'  => [],
                                    'Feeding Bra' => [],
                                    'Cup Bra'     => [],
                                    'Chudi Bra'   => [],
                                    'Saree Bra'   => [],
                                ],
                            ],
                            'Panties' => [],
                            'Slip' => [
                                'children' => [
                                    'Joy Cup (3 in 1)' => [],
                                    'Adjustment'       => [],
                                    'Non-Adjustment'   => [],
                                    'White'            => [],
                                ],
                            ],
                        ],
                    ],
                    'Jeans' => [
                        'children' => [
                            'Ankle'          => [],
                            'Baggy'          => [],
                            'Fish Cut Baggy' => [],
                            'Korean'         => [],
                        ],
                    ],
                    'Western' => [
                        'children' => [
                            'Short Top'          => [],
                            'T-Shirt (Normal)'   => [],
                            'Shorts'             => [],
                        ],
                    ],
                    'Shawls' => [
                        'children' => [
                            'Marble'            => [],
                            'Marble Printed'    => [],
                            'Mirror Shawl'      => [],
                            'Shimmer Shawl'     => [],
                            'Cotton Shawl'      => [],
                            'Stone Shawl'       => [],
                            'Embroidery Shawl'  => [],
                        ],
                    ],
                ],
            ],
        ];

        $this->build($tree, null);
    }

    protected function build(array $nodes, ?int $parentId): void
    {
        $order = 1;

        foreach ($nodes as $name => $config) {
            $category = Category::updateOrCreate(
                [
                    'parent_id' => $parentId,
                    'name'      => $name,
                ],
                [
                    'slug'        => $this->uniqueSlug($name, $parentId),
                    'description' => $config['description'] ?? null,
                    'is_featured' => $config['is_featured'] ?? false,
                    'status'      => true,
                    'sort_order'  => $order++,
                ]
            );

            if (! empty($config['children'])) {
                $this->build($config['children'], $category->id);
            }
        }
    }

    /**
     * "Bra" under Inners and a future "Bra" elsewhere must not collide.
     */
    protected function uniqueSlug(string $name, ?int $parentId): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 2;

        while (
            Category::where('slug', $slug)
                ->where(fn ($q) => $q->where('name', '!=', $name)->orWhere('parent_id', '!=', $parentId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}