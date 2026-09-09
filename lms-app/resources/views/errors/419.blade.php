@extends('errors.layout')

@section('title', __('Phiên làm việc đã hết hạn'))
@section('code', '419')
@section('icon', 'history_toggle_off')
@section('message', __('Phiên bảo mật của bạn đã hết hạn do không hoạt động trong một thời gian dài. Vui lòng tải lại trang để tiếp tục.'))
@section('action_reload', true)
