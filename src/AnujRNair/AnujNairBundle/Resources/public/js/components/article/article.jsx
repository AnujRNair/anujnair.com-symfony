import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { Post } from '@anujnair/js/types/post';
import { User } from '@anujnair/js/types/user';
import { Tags } from '@anujnair/js/types/tag';

import './article.scss';

export default class Article extends Component {
  static propTypes = {
    post: Post,
    user: User,
    showMore: PropTypes.bool,
    tags: Tags
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
      <li className={'icon icon-tags'}>
        Tags:
        <ul className={'article__metadata-tags'}>{items}</ul>
      </li>
    );
  }

  renderMetaData() {
    return (
      <ul className={'article__metadata'}>
        <li className={'icon icon-man'}>
          Author: {this.props.user.firstName} {this.props.user.lastName}
        </li>
        <li className={'icon icon-calendar'}>
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
        href={`/blog/${this.props.post.id}-${this.props.post.urlTitle}`}
        className={'article__show-more'}
      >
        Show more
      </a>
    );
  }

  render() {
    return (
      <article
        className={'article'}
        itemScope
        itemType={'http://schema.org/Article'}
      >
        <h2 className={'article__title'}>
          <a href={`/blog/${this.props.post.id}-${this.props.post.urlTitle}`}>
            {this.props.post.title}
          </a>
        </h2>
        {this.renderMetaData()}
        <p
          className={'article__contents'}
          dangerouslySetInnerHTML={{ __html: this.props.post.contents }}
        />
        {this.renderShowMore()}
      </article>
    );
  }
}
