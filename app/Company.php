<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    private static $URL = 'http://avoindata.prh.fi:80/tr/v1?totalResults=true&maxResults=1000&resultsFrom='

    protected $fillable = [
        'business_id',
        'company_form',
        'name',
        'registration_date'
    ];

    public function addresses()
    {
        $this->hasMany('App\Address');
    }

    public function createdBy()
    {
        $this->belongsTo('App\User');
    }

    public function businessLines()
    {
        $this->belongsToMany('App\BusinessLine');
    }

    /*
     * This function is used to get daily updates from the PRH api
     */
    public static  function updateDaily()
    {
        $url = self::$URL . 'companyRegistrationFrom=2015-11-16&companyChangedSince=2015-11-16';
        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $result=curl_exec($ch);
        curl_close($ch);
        $resultObject = json_decode($result);
        foreach ($resultObject->results as $co) {
            // find company by business_id
            $company = Company::where('business_id', $co->businessId)->first();
            // if company not found -> create
            if ( is_null($company)) {
                $company = Company::create([
                    'business_id' => $co->businessId,
                    'name' => $co->name,
                    'company_form' => $co->companyForm,
                    'registration_date' => $co->registrationDate,
                ]);

                $addedCompanies[] = $company->businessId;

            }

        }

    }

    public static function fetchAllDataFromPrh($start = 0, $end = 350000)
    {
        //init added companies array
        $addedCompanies = [];
        for ($i = $start; $i < $end ; $i = $i + 1000) {
            $url = self::$URL . $i . '&companyRegistrationFrom=1800-01-01';
            $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            $result=curl_exec($ch);
            curl_close($ch);
            $resultObject = json_decode($result);
            foreach ($resultObject->results as $co) {
                // find company by business_id
                $company = Company::where('business_id', $co->businessId)->first();
                // if company not found -> create
                if ( is_null($company)) {
                    $company = Company::create([
                        'business_id' => $co->businessId,
                        'name' => $co->name,
                        'company_form' => $co->companyForm,
                        'registration_date' => $co->registrationDate,
                    ]);

                    $addedCompanies[] = $company->businessId;

                }

            }

        }
        return true;

    }

}
