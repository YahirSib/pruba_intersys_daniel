<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalog;

class CatalogController extends Controller
{
    public function fetchCatalog()
    {
        $catalogs = Catalog::all();
        return response()->json($catalogs);
    }
}
