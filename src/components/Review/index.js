import { __ } from '@wordpress/i18n';
import StarRating from '../StarRating';
import BlankAvatar from '../../images/blank-avatar.png';
import { Icon } from '@wordpress/components';
import IconGoogle from '../../images/sprite-google-places.png';

export default function Review( { index, review = [] } ) {

    return (
        <div className={'rby-business-review'} key={index}>
            <img src={IconGoogle} className={'rby-business-review-yelp-icon'} />
            <div className={'rby-business-review-user'}>
                <div className={'rby-business-review-user-image'}>
                    {review.profile_photo_url && (
                        <img src={review.profile_photo_url} alt={review.author_name} />
                    )}
                    {!review.profile_photo_url && (
                        <img src={BlankAvatar} alt={review.author_name} />
                    )}

                </div>
                <div className={'rby-business-review-user-name'}>
                    {review.author_name}
                </div>
            </div>
            <div className={'rby-business-review-content'}>
                <div className={'rby-business-review-content-rating'}>
                    <StarRating
                        overallRating={review.rating}
                        date={review.time}
                    />
                </div>
                <div className={'rby-business-review-content-text'}>
                    <p>{review.text}</p>
                    <div className={'rby-business-review-content-readmore-wrap'}>
                        <a href={review.author_url} target={'_blank'}
                           className={'rby-business-review-content-readmore'}>{__( 'Read more', 'google-places-reviews' )}
                            <Icon icon={'external'} size={'100'} /></a>

                    </div>
                </div>
            </div>
        </div>
    );

}
