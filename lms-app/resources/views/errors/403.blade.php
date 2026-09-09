@extends('errors.layout')

@section('title', __('Không có quyền truy cập'))
@section('code', '403')
@section('icon', 'gpp_bad')
@section('message', __('Bạn không có quyền truy cập vào khu vực này. Nếu bạn cho rằng đây là sự nhầm lẫn, vui lòng liên hệ quản trị viên.'))
