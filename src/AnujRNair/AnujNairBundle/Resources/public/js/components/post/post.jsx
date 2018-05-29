import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { Post as PostProps } from '@anujnair/js/types/post';
import { User } from '@anujnair/js/types/user';
import { Tags } from '@anujnair/js/types/tag';

import './post.scss';

export default class Post extends Component {
  static propTypes = {
    post: PostProps.isRequired,
    showMore: PropTypes.bool,
    tags: Tags.isRequired,
    user: User.isRequired
  };

  static defaultProps = {
    showMore: false
  };

  renderTagsMetaData() {
    if (this.props.tags.length === 0) {
      return null;
    }

    const items = this.props.tags.map(tag => (
      <li key={tag.id}>
        <a href={`/blog/t/${tag.id}-${tag.urlName}`}>{tag.name}</a>
      </li>
    ));

    return (
      <li className="icon icon-tags">
        Tags:
        <ul className="post__metadata-tags">{items}</ul>
      </li>
    );
  }

  renderMetaData() {
    return (
      <ul className="post__metadata">
        <li className="icon icon-man">
          Author: {this.props.user.firstName} {this.props.user.lastName}
        </li>
        <li className="icon icon-calendar" itemProp="datePublished">
          Published: {this.props.post.datePublished}
        </li>
        {this.renderTagsMetaData()}
      </ul>
    );
  }

  renderShowMore() {
    if (this.props.showMore === false) {
      return null;
    }

    return (
      <a
        className="post__show-more"
        href={`/blog/${this.props.post.id}-${this.props.post.urlTitle}`}
      >
        Show more
      </a>
    );
  }

  render() {
    return (
      <article className="post" itemScope itemType="http://schema.org/Article">
        <h2 className="post__title" itemProp="headline">
          <a href={`/blog/${this.props.post.id}-${this.props.post.urlTitle}`}>
            {this.props.post.title}
          </a>
        </h2>
        {this.renderMetaData()}
        <p
          className="post__contents"
          dangerouslySetInnerHTML={{ __html: this.props.post.contents }}
          itemProp="description"
        />
        {this.renderShowMore()}
      </article>
    );
  }
}
