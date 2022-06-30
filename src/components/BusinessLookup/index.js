import { __ } from '@wordpress/i18n';
import { useState, useEffect, useRef } from '@wordpress/element';
import { TextControl } from '@wordpress/components';
import GoogleLogo from '../../images/google-logo.svg';

let autoComplete;

const loadScript = ( url, callback ) => {
    let script = document.createElement( 'script' );
    script.type = 'text/javascript';

    if ( script.readyState ) {
        script.onreadystatechange = function() {
            if ( script.readyState === 'loaded' || script.readyState === 'complete' ) {
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {
        script.onload = () => callback();
    }

    script.src = url;
    document.getElementsByTagName( 'head' )[0].appendChild( script );
};

const BusinessLookup = ( { setAttributes } ) => {

    const autoCompleteRef = useRef( null );
    const [location, setLocation] = useState( '' );
    const urlGoogleMaps = `https://maps.googleapis.com/maps/api/js?key=${window.googleBlockSettings.apiKey}&libraries=places`;

    useEffect( () => {
        let loadedScripts = Array.from( document.querySelectorAll( 'script' ) ).map( scr => scr.src );
        if ( loadedScripts.includes( urlGoogleMaps ) ) {
            handleScriptLoad( setLocation, autoCompleteRef );
            return;
        }

        loadScript(
            urlGoogleMaps,
            () => handleScriptLoad( setLocation, autoCompleteRef ),
        );
    }, [] );

    const handleScriptLoad = ( setLocation, autoCompleteRef ) => {
        autoComplete = new window.google.maps.places.Autocomplete(
            autoCompleteRef.current,
            { types: ['establishment'] },
        );
        setAttributes( { location } );

        autoComplete.addListener( 'place_changed', () => {
                const { place_id, name } = autoComplete.getPlace();
                setAttributes( {
                    placeId: place_id,
                    reference: place_id,
                    location: name,
                } );
            },
        );
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
                        ref={autoCompleteRef}
                        onChange={event => setLocation( event )}
                        help={__(
                            'Enter the name of your place (business name, address, location).',
                            'google-places-reviews',
                        )}
                    />
                </div>

            </div>
        </div>
    );
};

export default BusinessLookup;
