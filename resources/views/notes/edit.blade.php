@extends('layouts.app')
@section('title', 'Edit Catatan — Notes App')
@section('content')
    @livewire('notes.note-form', ['note' => $note])
@endsection
