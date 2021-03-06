
@extends('app')

@section('title')
    Pesanan
@endsection

@section('content')
    <br>
    <div class="well well-sm">

        <ul class="nav nav-pills nav-justified">
            <li>{!!link_to('pesanan/index','List')!!}</li>
            <li>{!!link_to('pesanan/create','Tambah')!!}</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">Detail Pesanan Barang</div>
                <div class="panel-body">
                    @if(sizeof($pesananBarang))
                        <div class="form-group">
                            <label class="col-md-2 control-label">ID </label>
                            <div class="col-md-2">
                                <label class="control-label">PS-{{$pesananBarang->id}}</label>
                            </div>
                            <div class="col-md-6">
                                @if(Auth::user()->name == 'SUPERVISOR')
                                    
                                @else
                                    
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Pemesan </label>
                            <div class="col-md-2">
                                <label class="control-label">{{$pesananBarang->pemesan}}</label>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Tanggal </label>
                            <div class="col-md-2">
                                <label class="control-label">{{$pesananBarang->tanggal}}</label>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Catatan </label>
                            <div class="col-md-2">
                                <label class="control-label">{{$pesananBarang->catatan}}</label>
                            </div>
                        </div>
                        <br>
                        <table class="table table-hover">
                            <tr>
                                <th>Kode Barang</th>
                                <th>Kuantitas</th>
                                <th>Tanggal Dibutuhkan</th>

                            </tr>
                            @foreach($pesananBarang->barangs as $data)
                                <tr>
                                    <td>{{$data->id.' - '.$data->nama}}</td>
                                    <td>{{$data->pivot->kuantitas}}</td>
                                    <td>{{$data->pivot->tanggal_butuh}}</td>
                                </tr>
                            @endforeach
                        </table>
                        <br>
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <br>
                                <label class="control-label">Konfirmasi : </label>
                                <br>
                            </div>
                            <br>

                            @if(Auth::user()->name == 'SUPERVISOR')
                                    <div class="col-md-1">
                                        {!! Form::open(['url' => 'pesanan/diterima/'.$pesananBarang->id, 'class' => 'form-horizontal']) !!}
                                            <button class="btn btn-success btn-sm">Terima</button>
                                        {!!Form::close()!!}
                                    </div>
                                    <div class="col-md-1">
                                        {!! Form::open(['url' => 'pesanan/menunggu/'.$pesananBarang->id, 'class' => 'form-horizontal']) !!}
                                        <button class="btn btn-warning btn-sm">Tunggu</button>
                                        {!!Form::close()!!}
                                    </div>
                                    <div class="col-md-1">
                                        {!! Form::open(['url' => 'pesanan/ditolak/'.$pesananBarang->id, 'class' => 'form-horizontal']) !!}
                                            <button class="btn btn-danger btn-sm">Tolak</button>
                                            <br>

                                        {!!Form::close()!!}
                                    </div>
                                    <br>
                                    <br>
                                    <div class="col-md-3">
                                            <label class="control-label">Keterangan :</label>
                                    </div>                                 
                                    {!! Form::open(['url' => 'pesanan/keterangan/'.$pesananBarang->id, 'class' => 'form-horizontal']) !!}
                                    
                                    <br>
                                    <div class="col-md-8">
                                        <textarea class="form-control" type="text" name="keterangan" placeholder="hanya dapat diisi admin">{{$pesananBarang->keterangan}}</textarea>
                                    </div>
                                    <br>
                                    <div class="col-md-offset-9 col-md-3">
                                        <button type="submit" class="btn btn-primary btn-sm">Beri keterangan</button>
                                    </div>
                                {!!Form::close()!!}
                            @else
                            @if($pesananBarang->status == 'Belum dikonfirmasi')
                                        <label class="control-label"><span class="label label-default">{{$pesananBarang->status}}</span></label >
                                    @elseif($pesananBarang->status == 'Diterima')
                                        <label class="control-label"><span class="label label-success">{{$pesananBarang->status}}</span></label >
                                    @elseif($pesananBarang->status == 'Ditolak')
                                        <label class="control-label"><span class="label label-danger">{{$pesananBarang ->status}}</span></label >
                                    @else
                                        <label class="control-label"><span class="label label-warning">{{$pesananBarang->status}}</span></label >
                                    @endif
                                    <br>
                                <label class="col-md-3 control-label">Keterangan :</label>

                                <label class="control-label">{{$pesananBarang->keterangan}}</label>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection