<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    private static $URL = 'http://avoindata.prh.fi:80/tr/v1?totalResults=true&maxResults=1000&resultsFrom=';

    /*
     * Get data from url and return an array
     * @return array
     */
    private static function fetchData($URL)
    {
        $url = $URL;
        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $result=curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    /*
     * @return $data = [
     *    int 'business_id',
     *    string 'name'
     *    string 'companyForm',
     *    date 'registrationDate'
     * ]
     */
    private static function saveData($data)
    {
        // find company by business_id
        $company = Company::where('business_id', $data->businessId)->first();
        // if company not found -> create
        if ( is_null($company)) {
            $company = Company::create([
                'business_id' => $data->businessId,
                'name' => $data->name,
                'company_form' => $data->companyForm,
                'registration_date' => $data->registrationDate,
            ]);

            $addedCompanies[] = $company->businessId;

        }
        return $addedCompanies;
    }

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
     * @return ids of added companies
     */
    public static function updateNewDaily()
    {
        $date = new DateTime();
        $date->add(DateInterval::createFromDateString('yesterday'));
        $url = self::$URL . '&companyRegistrationFrom=' . $date->format('Y-m-d');
        $data = $self::fetchData($url);
        $ids = self::saveData($data);
        return $ids;
    }

    public static function updateExistingDaily()
    {
      $date = new DateTime();
      $date->add(DateInterval::createFromDateString('yesterday'));
      $url = self::$URL . '&companyChangedSince=' . $date->format('Y-m-d');
      $data = $self::fetchData($url);
      $ids = self::saveData($data);
      return $ids;
    }

    /*
     * Get the initial data for database
     */
    public static function fetchAllDataFromPrh($start = 0, $end = 350000)
    {
        $url = self::$URL . $i . '&companyRegistrationFrom=1800-01-01';
        for ($i = $start; $i < $end ; $i = $i + 1000) {
            $data = self::fetchData($url);
            $ids[] .= self::saveData($data);
        }
        return $ids;
    }
}
