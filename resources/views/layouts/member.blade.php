{{-- Espace adhérent ou visiteur (selon le rôle du compte) : mise en page commune des espaces connectés. --}}
@extends('layouts.espace', ['espaceArea' => auth()->user()?->hasRole('visitor') ? 'visitor' : 'member'])
