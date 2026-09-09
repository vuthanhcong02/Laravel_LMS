@extends('errors.layout')

@section('title', __('Yêu cầu không hợp lệ'))
@section('code', '400')
@section('icon', 'error')
@section('message', __('Yêu cầu gửi lên máy chủ không đúng định dạng hoặc thiếu thông tin cần thiết. Vui lòng kiểm tra lại thao tác.'))
