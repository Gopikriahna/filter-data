<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departmentmodel extends Model
{
    use HasFactory;
    protected $table="companyworklocations";
    protected $fillable=['id','company_id','type','status','country','state','contry_code','state_code','city'];
}
