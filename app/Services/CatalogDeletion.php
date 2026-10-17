<?php

namespace App\Services;

use App\Models\CatalogModel;
use App\Models\ProductCategory;
use App\Models\Unit;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CatalogDeletion
{
    public static function delete(CatalogModel $record): void
    {
        try {
            DB::transaction(function () use ($record) {
                $locked = $record->newQuery()->lockForUpdate()->findOrFail($record->id);
                if (($locked instanceof ProductCategory && ($locked->children()->exists() || $locked->products()->exists())) || ($locked instanceof Unit && $locked->products()->exists())) {
                    throw ValidationException::withMessages(['delete' => 'Không thể xóa dữ liệu đang được tham chiếu.']);
                }
                $locked->delete();
            }, 3);
        } catch (QueryException $error) {
            if (($error->errorInfo[1] ?? null) === 1451) {
                throw ValidationException::withMessages(['delete' => 'Dữ liệu vừa được tham chiếu; thao tác xóa đã bị từ chối.']);
            }
            throw $error;
        }
    }
}
