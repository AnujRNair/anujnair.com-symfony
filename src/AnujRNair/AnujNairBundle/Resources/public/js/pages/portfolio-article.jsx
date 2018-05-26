import React, { Component, Fragment } from 'react';

import { Tags } from '@anujnair/js/types/tag';
import { Article } from '@anujnair/js/types/article';
import { Archive, Similar } from '@anujnair/js/types/post';

import BlogArchive from '@anujnair/js/components/blog-archive/blog-archive.jsx';
import SimilarPosts from '@anujnair/js/components/similar-posts/similar-posts.jsx';

export default class PortfolioArticle extends Component {
  static propTypes = {
    article: Article,
    tags: Tags
  };

  render() {
    return <div />;
  }
}

export class PortfolioAside extends Component {
  static propTypes = {
    archive: Archive,
    similar: Similar
  };

  renderArchive() {
    return (
      <BlogArchive
        icon={'icon-calendar'}
        list={this.props.archive}
        urlPath={'blog'}
      />
    );
  }

  renderSimilar() {
    return (
      <SimilarPosts
        icon={'icon-calendar'}
        list={this.props.similar}
        urlPath={'portfolio'}
      />
    );
  }

  render() {
    return (
      <Fragment>
        {this.renderSimilar()}
        {this.renderArchive()}
      </Fragment>
    );
  }
}
