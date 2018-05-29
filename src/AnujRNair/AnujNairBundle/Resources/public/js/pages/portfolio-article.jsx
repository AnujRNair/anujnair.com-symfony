import React, { Component, Fragment } from 'react';

import { Tags } from '@anujnair/js/types/tag';
import { Article as ArticleProps } from '@anujnair/js/types/article';
import { Archive, Similar } from '@anujnair/js/types/post';

import Article from '@anujnair/js/components/article/article';
import BlogArchive from '@anujnair/js/components/blog-archive/blog-archive';
import SimilarPosts from '@anujnair/js/components/similar-posts/similar-posts';

export default class PortfolioArticle extends Component {
  static propTypes = {
    article: ArticleProps.isRequired,
    tags: Tags.isRequired
  };

  render() {
    return <Article article={this.props.article} tags={this.props.tags} />;
  }
}

export class PortfolioAside extends Component {
  static propTypes = {
    archive: Archive.isRequired,
    similar: Similar.isRequired
  };

  renderArchive() {
    return (
      <BlogArchive
        icon="icon-calendar"
        list={this.props.archive}
        urlPath="blog"
      />
    );
  }

  renderSimilar() {
    return (
      <SimilarPosts
        icon="icon-calendar"
        list={this.props.similar}
        urlPath="portfolio"
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
