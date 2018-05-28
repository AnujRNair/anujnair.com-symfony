import React, { Component, Fragment } from 'react';

import { ContactForm } from '@anujnair/js/types/form';

import './contact.scss';

export default class Contact extends Component {
  static propTypes = {
    form: ContactForm
  };

  render() {
    return (
      <Fragment>
        <h3>Let&apos;s Connect!</h3>
        <form
          className={'contact'}
          action={'/about/'}
          name={'anujnair_contact_form'}
          method={'post'}
        >
          <label
            className={'contact__group'}
            htmlFor={'anujnair_contact_form[name]'}
          >
            <span className={'icon icon-man'} />
            <input
              type={'text'}
              id={'anujnair_contact_form[name]'}
              name={'anujnair_contact_form[name]'}
              value={'An'}
            />
          </label>

          <label
            className={'contact__group'}
            htmlFor={'anujnair_contact_form[email]'}
          >
            <span className={'icon icon-mail-full'} />
            <input
              type={'text'}
              id={'anujnair_contact_form[email]'}
              name={'anujnair_contact_form[email]'}
              value={'An'}
            />
          </label>

          <label
            className={'contact__group'}
            htmlFor={'anujnair_contact_form[subject]'}
          >
            <span className={'icon icon-pencil-full'} />
            <input
              type={'text'}
              id={'anujnair_contact_form[subject]'}
              name={'anujnair_contact_form[subject]'}
              value={'An'}
            />
          </label>

          <label
            className={'contact__group'}
            htmlFor={'anujnair_contact_form[contents]'}
          >
            <span className={'icon icon-chats-full'} />
            <textarea
              id={'anujnair_contact_form[contents]'}
              name={'anujnair_contact_form[contents]'}
              rows={4}
              value={'An'}
            />
          </label>

          <input
            type={'hidden'}
            name={'anujnair_contact_form[_token]'}
            value={this.props.form.csrf}
          />

          <input
            type={'submit'}
            name={'anujnair_contact_form[send]'}
            value={'Submit'}
          />
        </form>
      </Fragment>
    );
  }
}
