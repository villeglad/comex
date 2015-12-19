<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = Company::create($request->all());
        return $company;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return view('companies.show', compact('company'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function fetchAllCompaniesFromYtj()
    {
        
        for ($i = 0; $i < 350000 ; $i = $i + 1000) {
            $url = 'http://avoindata.prh.fi:80/tr/v1?totalResults=true&maxResults=1000&resultsFrom='. $i .'&companyRegistrationFrom=1800-01-01';
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
                $company = Company::create([
                    'business_id' => $co->businessId,
                    'name' => $co->name,
                    'company_form' => $co->companyForm,
                    'registration_date' => $co->registrationDate,
                ]);
            }
        }
        
        //dd(json_decode($result));
    }
}
