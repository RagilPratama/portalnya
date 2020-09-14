<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Provinsi;
use App\Models\Master\Kabupaten;
use App\Models\Master\Kecamatan;
use App\Models\Master\Kelurahan;
use App\Models\Master\RW;
use App\Models\Master\RT;
use DB;

class UserAkses extends BaseModel
{   
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }
    
    protected $table = 'UserAkses';
    protected $primaryKey = 'ID';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'UserID', 'ID');
    }
    
    public function byUserID($userID)
    {
        $rows = $this->where('UserID', $userID)->get();
        return $rows;
    }
    
    public function postUpdate($request)
    {
        
        try {
            $userID = $request->userid;
            $tk = max(array_keys($request->wilayahid));
            $tkuser = User::find($userID)->TingkatWilayahID;
            // if ($tk <> $tkuser || empty($request->wilayahid[$tk])) {
            //     throw new \Exception('Wilayah belum dipilih');
            // }
            $data = $request->wilayahid[$tkuser] ?? [];
            DB::transaction(function () use($userID, $data) {
                
                $this->where('UserID', $userID)->delete();
                
                $insdata = [];
                foreach ((array)$data as $item) {
                    $rowdata = [
                        'UserID' => $userID,
                        'WilayahID' => $item
                    ];
                    $insdata[] = $rowdata;
                }
                
                if (!empty($insdata)) $this->insert($insdata);
                
            });
            
            User::where('ID', $userID)->update(
                [
                    'LastModified' => date('Y-m-d H:i:s'),
                    'LastModifiedBy' => currentUser('UserName')
                ]
            );
            
            // send new user email

            $muser = new User();
            $muser->sendNewUserEmail($userID);
            
            $this->jsonResponse->data = $data;
            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }
    
    public function postUpdateAkses()
    {
        
        try {
            $userID = request()->input('UserID');
            $tree = request()->input('tree');
            // $targetKK = array_filter($tree, function($obj) {
            //     if ($obj['Flag']==1) {
            //         return $obj;
            //     }
            // });
            $data = [];
            
            $this->where('UserID', $userID)->delete();
            $TingkatWilayahID = User::find($userID)->TingkatWilayahID;
            // debug($tree);exit;
            if (!empty($tree)) {
            foreach ($tree as $row) {
                if ($row['Flag']==1) {
                    //check overlap wilayah
                    $sql = 'SELECT * FROM "User" a INNER JOIN "UserAkses" b ON a."ID"=b."UserID" AND b."WilayahID"=? WHERE a."TingkatWilayahID"=? AND a."ID" <> ?';
                    $checkrow = \DB::select($sql, [$row['WilayahID'], $TingkatWilayahID, $userID]);
                    // debug($row['WilayahID'].'-'. $TingkatWilayahID.'-'. $userID, 1);
                    if (!empty($checkrow)) {
                        throw new \Exception('Wilayah sudah ditetapkan ke pengguna lain');
                    }

                    $maxValue = UserAkses::orderBy('ID', 'desc')->value('ID');
                    $item = [];
                    $item['ID'] = $maxValue+1;// \DB::raw("nextval('\"UserAkses_ID_seq\"')");;
                    $item['UserID'] = $userID;
                    $item['WilayahID'] = $row['WilayahID'];
                    // $item['TargetKK'] = $row['TargetKK']??0;
                    $data[] = $item;
                    
                    $this->insert($item);
                }
            }
            }
            
            $muser = new User();
            
            User::where('ID', $userID)->update(
                [
                    'LastModified' => date('Y-m-d H:i:s'),
                    'LastModifiedBy' => currentUser('UserName')
                ]
            );
            
            // send new user email
            $muser->sendNewUserEmail($userID);
            
            $this->jsonResponse->data = $data;
            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Data berhasil disimpan';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }
    
}
