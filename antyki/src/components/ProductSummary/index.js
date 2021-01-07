import React from 'react'
import Img from 'gatsby-image'

import {Item, Label} from 'semantic-ui-react'

export default ({id, name, meta, sku, mainImage}) => (
  <Item.Group>
    <Item style={{alignItems: 'center'}}>
      <Item.Image size="medium">
        {id}
        <Img
          style={{width: '250px'}}
          sizes={mainImage.childImageSharp.sizes}
          alt={name}
        />
      </Item.Image>
      <Item.Content>
        <Item.Header>{name}</Item.Header>
        <Item.Description>
          <p>{meta.display_price.with_tax.formatted}</p>
          <Label>{`SKU: ${sku}`}</Label>
        </Item.Description>
        <Item.Extra>extra</Item.Extra>
      </Item.Content>
    </Item>
  </Item.Group>
)
