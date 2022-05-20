import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import {
    PanelBody,
    PanelRow,
    ToggleControl,
    TextControl,
    CheckboxControl,
    Button, ResponsiveWrapper,
} from '@wordpress/components';
import { Fragment, useState, createRef, useEffect } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import './editor.scss';
import WelcomeScreen from './components/WelcomeScreen';
import BusinessLookup from './components/BusinessLookup';
import GoogleBlock from './components/GoogleBlock';
import runLottieAnimation from './helperFunctions/runLottieAnimation';

/**
 * Edit function.
 *
 *  @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
    const {
        placeId,
        showHeader,
        mediaId,
        mediaUrl,
        showBusinessRating,
        showReviewButton,
        showBusinessMeta,
        showBusinessInfo,
        showPhone,
        showHours,
        showLocation,
        showReviews,
        preview,
    } = attributes;

    const [googleConnected, setGoogleConnected] = useState( false );

    const handleApiKeyChange = ( value ) => {
        setGoogleConnected( value );
    };

    const removeMedia = () => {
        setAttributes( {
            mediaId: 0,
            mediaUrl: ''
        } );
    };

    const onSelectMedia = ( media ) => {
        setAttributes( {
            mediaId: media.id,
            mediaUrl: media.url
        } );
    };

    const media = useSelect( ( select ) => {
        return select( 'core' ).getMedia( mediaId );
    }, [onSelectMedia] );

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

    const userIsAdmin = useSelect( ( select ) => {
        return select( 'core' ).canUser( 'create', 'users' );
    }, [] );

    const isPlaceIdSet = () => attributes.placeId.trim().length !== 0;

    // Run Lotties.
    useEffect( () => {
        // business search screen
        if ( googleConnected && !placeId ) {
            runLottieAnimation( 'search', 'google-block-admin-lottie-search' );
        }
    }, [googleConnected, placeId] );

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
                                    <>
                                        <PanelRow>
                                            <ToggleControl
                                                label={__( 'Display Header', 'donation-form-block' )}
                                                help={__(
                                                    'Do you want to display the business name, overall rating, images, price point, more?',
                                                    'donation-form-block',
                                                )}
                                                className={'dfb-stripe-link-toggle'}
                                                checked={showHeader}
                                                onChange={( value ) => {
                                                    setAttributes( { showHeader: value } );
                                                }}
                                            />
                                        </PanelRow>
                                        {showHeader && (
                                            <div className={'rbg-admin-subfields-wrap'}>
                                                <PanelRow>
                                                    <div className="rbg-media-uploader">
                                                        <p className={'rbg-label'}>
                                                            <label>{__( 'Header Image', 'blocks-for-github' )}</label>
                                                        </p>
                                                        <MediaUploadCheck>
                                                            <MediaUpload
                                                                onSelect={onSelectMedia}
                                                                value={attributes.mediaId}
                                                                allowedTypes={['image']}
                                                                render={( { open } ) => (
                                                                    <Button
                                                                        className={attributes.mediaId === 0 ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview'}
                                                                        onClick={open}
                                                                    >
                                                                        {attributes.mediaId === 0 && __( 'Choose an image', 'blocks-for-github' )}
                                                                        {media !== undefined &&
                                                                            <ResponsiveWrapper
                                                                                naturalWidth={media.media_details.width}
                                                                                naturalHeight={media.media_details.height}
                                                                            >
                                                                                <img src={media.source_url} />
                                                                            </ResponsiveWrapper>
                                                                        }
                                                                    </Button>
                                                                )}
                                                            />
                                                        </MediaUploadCheck>
                                                        <div className="rbg-media-btns">
                                                            {attributes.mediaId !== 0 &&
                                                                <MediaUploadCheck>
                                                                    <MediaUpload
                                                                        title={__( 'Replace Image', 'blocks-for-github' )}
                                                                        value={attributes.mediaId}
                                                                        onSelect={onSelectMedia}
                                                                        allowedTypes={['image']}
                                                                        render={( { open } ) => (
                                                                            <Button onClick={open} isSmall variant="secondary" className={'rbg-replace-image-btn'}>{__( 'Replace Image', 'blocks-for-github' )}</Button>
                                                                        )}
                                                                    />
                                                                </MediaUploadCheck>
                                                            }
                                                            {attributes.mediaId !== 0 &&
                                                                <MediaUploadCheck>
                                                                    <Button onClick={removeMedia} isSmall variant="secondary">{__( 'Remove Image', 'blocks-for-github' )}</Button>
                                                                </MediaUploadCheck>
                                                            }
                                                        </div>
                                                        <p className={'rbg-help-text'}>{__( 'Upload or select an image for the header background.', 'blocks-for-github' )}</p>
                                                    </div>
                                                </PanelRow>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Business Rating', 'donation-form-block' )}
                                                        help={__(
                                                            'Check to display the overall business rating.',
                                                            'donation-form-block',
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showBusinessRating}
                                                        onChange={( value ) => {
                                                            setAttributes( { showBusinessRating: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Review Button', 'donation-form-block' )}
                                                        help={__(
                                                            'Check to display the "Write a Review" button.',
                                                            'donation-form-block',
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showReviewButton}
                                                        onChange={( value ) => {
                                                            setAttributes( { showReviewButton: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Meta', 'donation-form-block' )}
                                                        help={__(
                                                            'Check to display the business meta info like price point, open or closed, and price point.',
                                                            'donation-form-block',
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showBusinessMeta}
                                                        onChange={( value ) => {
                                                            setAttributes( { showBusinessMeta: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                            </div>
                                        )}
                                        <PanelRow>
                                            <ToggleControl
                                                label={__( 'Display Business Info', 'donation-form-block' )}
                                                help={__(
                                                    'Toggle on to display the business info section containing hours, location, and more.',
                                                    'donation-form-block',
                                                )}
                                                className={'dfb-stripe-link-toggle'}
                                                checked={showBusinessInfo}
                                                onChange={( value ) => {
                                                    setAttributes( { showBusinessInfo: value } );
                                                }}
                                            />
                                        </PanelRow>
                                        {showBusinessInfo && (
                                            <div className={'rbg-admin-subfields-wrap'}>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Phone', 'donation-form-block' )}
                                                        help={__(
                                                            'Toggle on to display the business phone number and additional business information.',
                                                            'donation-form-block',
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showPhone}
                                                        onChange={( value ) => {
                                                            setAttributes( { showPhone: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Hours', 'donation-form-block' )}
                                                        help={__(
                                                            'Toggle on to display the business hours.',
                                                            'donation-form-block',
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showHours}
                                                        onChange={( value ) => {
                                                            setAttributes( { showHours: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Location', 'donation-form-block' )}
                                                        help={__(
                                                            'Toggle on to display the business location.',
                                                            'donation-form-block',
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showLocation}
                                                        onChange={( value ) => {
                                                            setAttributes( { showLocation: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                            </div>
                                        )}
                                        <PanelRow>
                                            <ToggleControl
                                                label={__( 'Display Reviews', 'donation-form-block' )}
                                                help={__(
                                                    'Toggle on to display individual reviews (up to 3 total per locale).',
                                                    'donation-form-block',
                                                )}
                                                className={'dfb-stripe-link-toggle'}
                                                checked={showReviews}
                                                onChange={( value ) => {
                                                    setAttributes( { showReviews: value } );
                                                }}
                                            />
                                        </PanelRow>
                                    </>
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
