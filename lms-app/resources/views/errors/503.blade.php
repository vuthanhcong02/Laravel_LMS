@extends('errors.layout')

@section('title', __('Hệ thống đang bảo trì'))
@section('code', '503')
@section('icon', 'construction')
@section('message', __('XiaoMu đang được bảo trì hoặc nâng cấp để nâng cao chất lượng dịch vụ. Vui lòng quay lại sau ít phút.'))
@section('hide_auth', true)
@section('hide_home_btn', true)
@section('action_reload', true)
