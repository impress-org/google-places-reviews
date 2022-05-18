import GoogleLogo from '../../images/google-logo.svg';
import { __ } from '@wordpress/i18n';
import { dispatch, useSelect } from '@wordpress/data';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {
    TextControl,
    Button,
    Icon,
    Spinner,
} from '@wordpress/components';

const WelcomeScreen = ( props ) => {

    const [googleApiKey, setGoogleApiKey] = useState( '' );

    const testApiKey = ( e ) => {
        e.preventDefault();
        // Fetch REST API to test key.
        apiFetch( { path: `/google-block/v1/profile?apiKey=${googleApiKey}&keyValidation=true` } )
            .then( ( response ) => {
                // 🔑 👍 Key is good. Save it.
                dispatch( 'core' )
                    .saveEntityRecord( 'root', 'site', {
                        googleplacesreviews_options: {
                            google_places_api_key: googleApiKey,
                        },
                    } )
                    .then( () => {
                        props.handleApiKeyChange( true );
                        dispatch( 'core/notices' ).createErrorNotice(
                            __( '🎉 Success! You have connected to the Google Places API.', 'google-widget-pro' ),
                            {
                                isDismissible: true,
                                type: 'snackbar',
                            },
                        );

                    } );
            } )
            .catch( ( error ) => {
                // 🔑 👎 Key is bad.
                props.handleApiKeyChange( false );
                const errorMessage = `${__( '🙈️ Google API Error:', 'google-places-reviews' )} ${error.message} ${__(
                    'Error Code:',
                    'google-places-reviews',
                )} ${error.code}`;
                dispatch( 'core/notices' ).createErrorNotice( errorMessage, {
                    isDismissible: true,
                    type: 'snackbar',
                } );
            } );
    };

    return (
        <div id={'rbg-admin-welcome-wrap'}>
            <div className='rbg-admin-welcome-content-wrap'>
                <img
                    className={'rbg-admin-google-logo'}
                    src={GoogleLogo}
                    alt={__( 'Google Logo', 'google-places-reviews' )}
                />
                <div id={'yelp-block-admin-lottie-api'}></div>
                <h2 className={'rbg-admin-google-welcome-heading'}>
                    {__(
                        'Welcome to the Reviews Block for Google! Let’s get started.',
                        'google-places-reviews',
                    )}
                </h2>
                <p className={'rbg-admin-google-welcome-text'}>
                    {__(
                        'This plugin requires a Google Places API key to get started. Don\'t worry, we’ll help you get started.',
                        'google-places-reviews',
                    )}
                    {' '}
                    <a
                        href='https://wpbusinessreviews.com/documentation/platforms/google/'
                        target='_blank'
                        rel='noopener noreferrer'
                    >
                        {__(
                            'Learn how to create a key',
                            'google-places-reviews',
                        )}

                    </a>
                    <Icon icon={'external'} />
                </p>
                <form onSubmit={testApiKey}>
                    <TextControl
                        value={googleApiKey}
                        type={'password'}
                        help={__( 'Please enter your Google Places API key to use this block.', 'google-places-reviews' )}
                        onChange={( newApiKey ) => {
                            setGoogleApiKey( newApiKey );
                        }}
                    />
                    <Button
                        type={'submit'}
                        className={'rbg-admin-button'}
                        isPrimary
                    >
                        {__( 'Save API Key', 'google-places-reviews' )}
                    </Button>
                </form>

            </div>
        </div>
    );
};

export default WelcomeScreen;
