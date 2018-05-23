import React, { Component } from 'react';

import { Post } from '@anujnair/js/types/post';
import { User } from '@anujnair/js/types/user';
import { Tags } from '@anujnair/js/types/tag';

import './article.scss';

export default class Article extends Component {
  static propTypes = {
    post: Post,
    user: User,
    tags: Tags
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
          Published: {this.props.post.datePublished.date}
        </li>
        {this.renderTagsMetaData()}
      </ul>
    );
  }

  render() {
    return (
      <article className={'article'}>
        <h2>{this.props.post.title}</h2>
        {this.renderMetaData()}
        <p>{this.props.post.contents}</p>
      </article>
    );
  }
}
