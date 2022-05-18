import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextControl, SelectControl, Spinner, Button } from '@wordpress/components';
import { Fragment, useState, createRef, useEffect } from '@wordpress/element';
import GoogleLogo from './images/google-logo.svg';
import { dispatch, useSelect } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';
import './editor.scss';
import WelcomeScreen from './components/WelcomeScreen';
import BusinessLookup from './components/BusinessLookup';
import GoogleBlock from './components/GoogleBlock';

/**
 * Edit function.
 *
 *  @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
    const { placeId, preview } = attributes;

    const [googleConnected, setGoogleConnected] = useState( false );

    const handleApiKeyChange = ( value ) => {
        setGoogleConnected( value );
    };

    const siteSettings = useSelect( ( select ) => {
        return select( 'core' ).getEntityRecord( 'root', 'site' );
    }, [] );

    useEffect( () => {
        if ( siteSettings ) {
            const { googleplacesreviews_options } = siteSettings;
            if ( googleplacesreviews_options.google_places_api_key ) {
                setGoogleConnected( true );
            }
        }
    }, [siteSettings] );

    const [state, setState] = useState( {
        isClearingCache: false,
        cacheError: null,
        error: null,
    } );

    const userIsAdmin = useSelect( ( select ) => {
        return select( 'core' ).canUser( 'create', 'users' );
    }, [] );

    const isPlaceIdSet = () => attributes.placeId.trim().length !== 0;

    return (
        <Fragment>
            <Fragment>
                <InspectorControls>
                    {userIsAdmin && isPlaceIdSet() && (
                        <Fragment>
                            <Fragment>
                                <PanelBody
                                    title={__( 'Appearance Settings', 'google-places-reviews' )}
                                    initialOpen={true}
                                >
                                </PanelBody>
                            </Fragment>

                            <PanelBody
                                title={__( 'Google Place Settings', 'google-places-reviews' )}
                                initialOpen={true}
                            >

                                <div className='set-business'>
                                    <p>
                                        <strong>{__( 'Place Set:', 'google-places-reviews' )} </strong>
                                        {__(
                                            'You have successfully set the place for this block.',
                                            'google-places-reviews',
                                        )}
                                    </p>
                                    <TextControl
                                        disabled
                                        name='place_id'
                                        label={__( 'Location', 'google-places-reviews' )}
                                        value={attributes.location}
                                        help={__(
                                            'This is the name of the place returned by Google\'s Places API.',
                                            'google-places-reviews',
                                        )}
                                    />
                                    <TextControl
                                        disabled
                                        name='place_id'
                                        label={__( 'Location Place ID', 'google-places-reviews' )}
                                        value={attributes.placeId}
                                        help={__(
                                            'This is the name of the place returned by Google\'s Places API.',
                                            'google-places-reviews',
                                        )}
                                    />
                                    <Button
                                        isPrimary
                                        onClick={() => {
                                            setAttributes( {
                                                placeId: '',
                                                location: '',
                                            } );
                                        }}
                                    >
                                        {__( 'Change Place', 'google-places-reviews' )}
                                    </Button>
                                </div>

                            </PanelBody>
                        </Fragment>
                    )}
                </InspectorControls>
            </Fragment>

            <Fragment>
                <div {...useBlockProps()}>
                    {!googleConnected && !placeId && (
                        <WelcomeScreen
                            handleApiKeyChange={handleApiKeyChange}
                        />
                    )}
                    {googleConnected && !placeId && (
                        <BusinessLookup
                            setAttributes={setAttributes}
                        />
                    )}
                    {googleConnected && placeId && <GoogleBlock attributes={attributes} />}
                </div>
            </Fragment>
        </Fragment>
    );
}
