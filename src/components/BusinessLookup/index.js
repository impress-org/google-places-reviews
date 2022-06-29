import { __ } from '@wordpress/i18n';
import { useState, createRef } from '@wordpress/element';
import { TextControl, SelectControl } from '@wordpress/components';
import GoogleLogo from '../../images/google-logo.svg';


const BusinessLookup = ( { setAttributes } ) => {

    const locationRef = createRef();

    const [location, setLocation] = useState( '' );
    const [placeType, setPlaceType] = useState( 'all' );

    const handleSearch = ( location ) => {

        setAttributes( { location } );

        if ( location.trim().length < 3 ) {
            return;
        }
        const autocomplete = new google.maps.places.Autocomplete( locationRef.current, [placeType] );

        autocomplete.addListener( 'place_changed', () => {
            const { place_id, name } = autocomplete.getPlace();

            if ( !place_id ) {
                // return setState( {
                //     error: __( 'No place reference found for this location.', 'google-places-reviews' ),
                // } );
            }

            setAttributes( {
                placeId: place_id,
                reference: place_id,
                location: name,
            } );
        } );
    };

    return (
        <div id={'rbg-admin-business-lookup-wrap'}>
            <div className='rbg-admin-business-lookup'>
                <div className={'rbg-admin-business-lookup-content-wrap'}>
                    <img className={'rbg-admin-google-logo'} src={GoogleLogo} alt={'Google Logo'} />
                    <div id={'google-block-admin-lottie-search'}></div>
                    <h2 className={'rbg-admin-google-welcome-heading'}>
                        {__( 'Let’s find the place you’re looking for on Google!', 'google-places-reviews' )}
                    </h2>
                    <p className={'rbg-admin-google-welcome-text'}>
                        {__(
                            'Use the fields below to lookup the place you\'d like to display on Google.',
                            'google-places-reviews',
                        )}
                    </p>
                </div>
                <div className={'rbg-admin-search-fields-wrap'}>
                    <TextControl
                        className={'rbg-admin-field'}
                        name='place_id'
                        label={__( 'Place Name', 'google-places-reviews' )}
                        value={location}
                        onChange={( location ) => {
                            setLocation( location );
                            handleSearch( location );
                        }}
                        ref={locationRef}
                        help={__(
                            'Enter the name of your place (business name, address, location).',
                            'google-places-reviews',
                        )}
                    />

                    <SelectControl
                        label={__( 'Place Type', 'google-places-reviews' )}
                        value={placeType}
                        options={[
                            { label: __( 'All', 'google-places-reviews' ), value: 'all' },
                            { label: __( 'Addresses', 'google-places-reviews' ), value: 'address' },
                            {
                                label: __( 'Establishments', 'google-places-reviews' ),
                                value: 'establishment',
                            },
                            { label: __( 'Regions', 'google-places-reviews' ), value: '(regions)' },
                        ]}
                        onChange={( placeType ) => {
                            setPlaceType( placeType );
                            setAttributes( { placeType } );
                        }}
                        help={__(
                            'Specify how you would like to lookup your Google Place.',
                            'google-places-reviews',
                        )}
                    />
                </div>

            </div>
        </div>
    );
};

export default BusinessLookup;
