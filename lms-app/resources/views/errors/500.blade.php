@extends('errors.layout')

@section('title', __('Lỗi máy chủ'))
@section('code', '500')
@section('icon', 'dns')
@section('message', __('Đã xảy ra sự cố từ phía hệ thống máy chủ. Đội ngũ kỹ thuật XiaoMu đã được ghi nhận và đang khắc phục.'))
@section('action_reload', true)
