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
     * @param $data = [
     *    int 'business_id',
     *    string 'name'
     *    string 'companyForm',
     *    date 'registrationDate'
     * ]
     *
     * @return array of business ids
     */
    private static function saveData($data)
    {
        // find company by business_id
        $company = Company::where('business_id', $data->businessId)->first();

        $company_data = [
            'business_id' => $data->businessId,
            'name' => $data->name,
            'company_form' => $data->companyForm,
            'registration_date' => $data->registrationDate,
        ];

        if ( is_null($company)) { // if company not found -> create

            $company = Company::create($company_data);
            $addedCompanies[] = $company->businessId;

        } else { // if compnay exists -> update

            $company->update($company_data);
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
     * @return business ids of added companies
     */
    public static function updateNewDaily()
    {
        $date = new DateTime();

        $date->add(DateInterval::createFromDateString('yesterday'));

        $url = self::$URL . '&companyRegistrationFrom=' . $date->format('Y-m-d');

        $data = $self::fetchData($url);

        foreach($data as $single){
            $ids[] .= self::saveData($single);
        }

        return $ids;
    }

    /*
     * Update companies that already exist
     */
    public static function updateExistingDaily()
    {
      $date = new DateTime();

      //The data in PRH api lags one day so get info for yesterday
      $date->add(DateInterval::createFromDateString('yesterday'))->format('Y-m-d');

      //Construct the url from the base url and add a changed since
      $url = self::$URL . '&companyChangedSince=' . $date;

      $data = $self::fetchData($url);

      //Check whether a company can be found and save that company
      foreach($data as $single){
          if(Company::where('business_id', $data->businessId)->first()){
              $ids[] .= self::saveData($single);
          }
      }

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

            foreach($data as $single){
                $ids[] .= self::saveData($data);
            }
        }

        return $ids;
    }

}
