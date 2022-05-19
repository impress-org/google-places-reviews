import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { dispatch } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';
import { Icon, Spinner } from '@wordpress/components';

import GoogleLogo from '../../images/google-logo.svg';
import IconGoogle from '../../images/google-icon.svg';
import IconReview from '../../images/review-icon.png';
import DefaultBg from '../../images/default-bg.png';

import StarRating from '../StarRating';
import OpenHours from '../OpenHours';
import Address from '../Address';
import Review from '../Review';
import Priciness from '../Priciness';


const GoogleBlock = ( props ) => {

    const [isLoading, setIsLoading] = useState( true );
    const [businessData, setBusinessData] = useState( null );

    useEffect( () => {
        if ( props.attributes.placeId ) {
            // ⚙️ Fetch REST API to get Google data.
            apiFetch( { path: `/google-block/v1/profile?placeId=${props.attributes.placeId}` } )
                .then( ( response ) => {
                    setBusinessData( response );
                    setIsLoading( false );
                    console.log( response );
                } )
                .catch( ( error ) => {
                    const errorMessage = `${__( '🙈️ Google API Error:', 'google-places-reviews' )} ${error.message} ${__(
                        'Error Code:',
                        'google-places-reviews',
                    )} ${error.code}`;
                    dispatch( 'core/notices' ).createErrorNotice( errorMessage, {
                        isDismissible: true,
                        type: 'snackbar',
                    } );
                } );
        }
    }, [props.attributes.placeId] );

    return (
        <div id={`rbg-wrap`} className={`rbg-wrap`}>
            {isLoading && (
                <div className={'rbg-loading-content'}>
                    <img src={GoogleLogo} alt={__( 'Loading Google Place Data', 'google-places-reviews' )} />
                    <div className={'rbg-loading-text'}>
                        <Spinner />
                        {__( 'Loading Google Place Data', 'google-places-reviews' )}
                    </div>
                </div>
            )}
            {!isLoading && (
                <>
                    {props.attributes.showHeader && (
                        <>
                            <div className={'rbg-google-icon-header'}>
                                <img src={IconGoogle} alt={__( 'Yelp', 'google-places-reviews' )} />
                            </div>
                            <div className={`rbg-image-header`}
                                 style={{
                                     backgroundImage: `url(${DefaultBg})`,
                                 }}
                            >
                            </div>
                            <div className={`rbg-title-header`}>
                                <div className={`rbg-business-name-wrap`}>
                                    <h3 className={`rbg-business-name`}>{businessData.name}</h3>
                                </div>

                                <div className={'rbg-business-meta-wrap'}>
                                    {props.attributes.showBusinessRating && (
                                        <StarRating
                                            overallRating={businessData.rating}
                                            totalRatings={businessData.user_ratings_total}
                                        />
                                    )}
                                    {props.attributes.showBusinessMeta && (
                                        <div className={'rbg-business-status-meta-wrap'}>
                                            <div className={'rbg-business-status-meta-wrap__inner'}>
                                                {businessData.rating && (
                                                    <div className={'rbg-business-overall-rating'}>
                                                        {businessData.rating + ' ' + __( 'Rating', 'google-places-reviews' )}
                                                    </div>
                                                )}
                                                {businessData.price_level && (
                                                    <Priciness
                                                        priceLevel={businessData.price_level}
                                                    />
                                                )}
                                                {businessData.opening_hours.open_now && (
                                                    <span
                                                        className={'rbg-business-open-status rbg-business-open-status__open'}>
                                                        {__( 'Open', 'google-places-reviews' )}
                                                    </span>
                                                )}
                                                {!businessData.opening_hours.open_now && (
                                                    <span
                                                        className={
                                                            'rbg-business-open-status rbg-business-open-status__closed'
                                                        }
                                                    >
                                                        {__( 'Closed', 'google-places-reviews' )}
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                    {props.attributes.showReviewButton && (
                                        <div className={`rbg-button-wrap`}>
                                            <a
                                                href={`https://search.google.com/local/writereview?placeid=${props.attributes.placeId}`}
                                                target={'_blank'}
                                                className={`rbg-button rbg-button--link`}
                                            >
                                                <img
                                                    src={IconReview}
                                                    alt={__( 'Write a Review', 'google-places-reviews' )}
                                                    className={'rbg-button__icon'}
                                                />
                                                {__( 'Write a Review', 'google-places-reviews' )}
                                            </a>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </>
                    )}
                    {props.attributes.showBusinessInfo && (
                        <div className={'rbg-additional-info-wrap'}>
                            {props.attributes.showPhone && (
                                <div className={'rbg-additional-info-wrap__inner'}>
                                    <h4 className={'rbg-heading'}>{__( 'Phone and More', 'google-places-reviews' )}</h4>
                                    <div className={'rbg-business-phone-wrap'}>
                                        <Icon icon={'phone'} size={16} />
                                        <a href={`tel:${businessData.formatted_phone_number}`}
                                           title={`Call ${businessData.name}`}>
                                            {businessData.formatted_phone_number}
                                        </a>
                                    </div>
                                    <div className={'rbg-business-badges-wrap'}>
                                        {businessData.categories &&
                                            businessData.categories.map( ( category, index ) => (
                                                <span key={index} className={'rbg-badge'}>
                                                    {category.title}
                                                </span>
                                            ) )}
                                        {businessData.transactions &&
                                            businessData.transactions.map( ( category, index ) => (
                                                <span key={index} className={'rbg-badge'}>
                                                    {category}
                                                </span>
                                            ) )}
                                    </div>
                                </div>
                            )}
                            {props.attributes.showHours && (
                                <div className={'rbg-additional-info-wrap__inner'}>
                                    <h4 className={'rbg-heading'}>{__( 'Hours', 'google-places-reviews' )}</h4>
                                    <OpenHours hours={businessData.opening_hours.periods} />
                                </div>
                            )}
                            {props.attributes.showLocation && (
                                <div className={'rbg-additional-info-wrap__inner'}>
                                    <h4 className={'rbg-heading'}>{__( 'Location', 'google-places-reviews' )}</h4>

                                    <Address
                                        displayAddress={businessData.adr_address}
                                        alias={businessData.alias}
                                    />
                                    <div className={'rbg-directions-link-wrap'}>
                                        <a
                                            href={`https://www.yelp.com/map/${businessData.alias}`}
                                            target={'_blank'}
                                            className={`rbg-button rbg-button--white rbg-button--link`}
                                        >
                                            {__( 'Get Directions', 'google-places-reviews' )}
                                        </a>
                                    </div>
                                </div>
                            )}
                        </div>
                    )}
                    {props.attributes.showReviews && (
                        <div className={'rbg-business-reviews-wrap'}>
                            <h3 className={'rbg-heading'}>{__( 'Highlighted Reviews', 'google-places-reviews' )}</h3>
                            {businessData.reviews.map( ( review, index ) => {
                                return <Review key={index} review={review} />;
                            } )}
                        </div>
                    )}

                    <div className={'rbg-powered-by-wrap'}>
                        <div className={'rbg-powered-by'}>
                            <span>{__( 'Powered by', 'google-places-reviews' )}</span>
                            <img src={GoogleLogo} alt={__( 'Powered by Yelp', 'google-places-reviews' )} />
                        </div>
                    </div>
                </>
            )}
        </div>
    );
};

GoogleBlock.defaultProps = {
    attributes: [],
};
export default GoogleBlock;
