<?php
 header("Access-Control-Allow-Origin: *");
/*
 * Example PHP implementation used for the index.html example
 */
 
// DataTables PHP library
include( "./lib/DataTables.php" );
 
// Alias Editor classes so they are easy to use
use
    DataTables\Editor,
    DataTables\Editor\Field,
    DataTables\Editor\Format,
    DataTables\Editor\Mjoin,
    DataTables\Editor\Options,
    DataTables\Editor\Upload,
    DataTables\Editor\Validate,
    DataTables\Editor\ValidateOptions;
 
// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'product' )
    ->fields(
        Field::inst( 'product.id'),
        Field::inst( 'product.name')
                ->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'A name is required' ) 
                ) ),
        Field::inst( 'product.image' )
                ->validator( Validate::url( 
                    ValidateOptions::inst()
                        ->message( 'Image must be a link' ) 
                ) )
                ->validator( Validate::notEmpty( 
                    ValidateOptions::inst()
                        ->message( 'A image is required' ) 
                ) ),
        Field::inst( 'product.numberOnStock' )
                ->validator( Validate::minNum(0,'.', ValidateOptions::inst()
                        ->message( 'A number on stock must be a number greater than 0' ))  )
                ->validator( Validate::notEmpty( ValidateOptions::inst()
                        ->message( 'A number on stock is required' ) 
                ) ),
        Field::inst( 'product.description' )
                ->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'A description is required' ) 
                ) ),
        Field::inst( 'product.price'  )
                ->validator( Validate::minNum(0,'.', ValidateOptions::inst()
                        ->message( 'A price must be a number greater than 0' ))  )
                ->validator( Validate::notEmpty( ValidateOptions::inst()
                        ->message( 'A price is required' ) 
                ) ),
        Field::inst( 'product.productTypeId')
        ->options( Options::inst()
            ->table( 'product_type' )
            ->value( 'id' )
            ->label( 'name' )
        )
        ->validator( 'Validate::dbValues' ),
        Field::inst( 'product_type.name'  )
    )
    ->leftJoin('product_type', 'product_type.id', '=', 'product.productTypeId')
    ->process( $_POST )
    ->json();