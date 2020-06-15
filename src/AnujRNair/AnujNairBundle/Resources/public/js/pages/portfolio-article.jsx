import React, { Component, Fragment } from 'react';

import { Tags } from '@anujnair/js/types/tag';
import { Article as ArticleProps } from '@anujnair/js/types/article';
import { Archive, Similar } from '@anujnair/js/types/post';

import Article from '@anujnair/js/components/article/article';
import BlogArchive from '@anujnair/js/components/blog-archive/blog-archive';
import SimilarPosts from '@anujnair/js/components/similar-posts/similar-posts';

function PortfolioArticle({ article, tags }) {
  return <Article article={article} tags={tags} />;
}

PortfolioArticle.propTypes = {
  article: ArticleProps.isRequired,
  tags: Tags.isRequired,
};

export default PortfolioArticle;

export class PortfolioAside extends Component {
  static propTypes = {
    archive: Archive.isRequired,
    similar: Similar.isRequired,
  };

  shouldComponentUpdate() {
    return false;
  }

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
