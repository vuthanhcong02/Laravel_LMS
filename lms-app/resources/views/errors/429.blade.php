@extends('errors.layout')

@section('title', __('Quá nhiều yêu cầu'))
@section('code', '429')
@section('icon', 'speed')
@section('message', __('Bạn đang thực hiện thao tác quá nhanh. Vui lòng tạm dừng một chút trước khi thử lại.'))
@section('action_reload', true)
