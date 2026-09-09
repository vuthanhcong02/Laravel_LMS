@extends('errors.layout')

@section('title', __('Chưa đăng nhập'))
@section('code', '401')
@section('icon', 'lock')
@section('message', __('Bạn cần đăng nhập để truy cập trang này hoặc phiên làm việc đã kết thúc. Vui lòng đăng nhập và thử lại.'))
