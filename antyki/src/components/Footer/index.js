import React from 'react'
import {Link} from 'gatsby'
import styled from 'styled-components'

import {theme} from '../GlobalStyle/variables'

import Container from '../Container'

const StyledFooterWrapper = styled.footer`
  width: 100%;

  padding: 40px 0;

  background-color: ${theme.colors.lgrey2};

  @media ${theme.rwd('desktop')} {
    padding: 100px 0;
  }
`

const StyledFooter = styled.div`
  width: 100%;

  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;

  @media ${theme.rwd('desktop')} {
    flex-direction: row;
    align-items: flex-start;
    justify-content: space-between;
  }
`

const StyledFooterColumn = styled.div`
  flex: 0 1 100%;

  margin-bottom: 20px;

  @media ${theme.rwd('desktop')} {
    flex: 0 1 calc(100% / 3);
  }

  p {
    font-size: 0.8rem;
    line-height: 1.1rem;
  }
`

const StyledFooterTitle = styled.h4`
  margin-bottom: 10px;

  @media ${theme.rwd('desktop')} {
    margin-bottom: 20px;
  }
`

const StyledList = styled.ul``

const StyledListItem = styled.li`
  margin-top: 15px;
  margin-bottom: 15px;

  font-size: 0.8rem;

  @media ${theme.rwd('desktop')} {
    margin-top: 0;
    margin-bottom: 10px;
  }
`

const Footer = () => (
  <StyledFooterWrapper>
    <Container>
      <StyledFooter>
        <StyledFooterColumn>
          <StyledFooterTitle>Kontakt</StyledFooterTitle>
          <StyledList>
            <StyledListItem>
              <a href="mailto:kontakt@antyki-monki.pl">
                kontakt@antyki-monki.pl
              </a>
            </StyledListItem>
            <StyledListItem>
              <a href="tel:+48575347131">tel, WhatsApp: +48 575 347 121</a>
            </StyledListItem>
            <StyledListItem>
              <a href="tel:+48537224218">tel, WhatsApp: +48 537 224 218</a>
            </StyledListItem>
          </StyledList>
        </StyledFooterColumn>
        <StyledFooterColumn>
          <StyledFooterTitle>Nawigacja</StyledFooterTitle>
          <StyledList>
            <StyledListItem>
              <Link to="/">Strona główna</Link>
            </StyledListItem>
            <StyledListItem>
              <Link to="/o-nas/">O nas</Link>
            </StyledListItem>
          </StyledList>
        </StyledFooterColumn>
        <StyledFooterColumn>
          <StyledFooterTitle>O nas</StyledFooterTitle>
          <p>
            W ofercie posiadamy komplety stołów z krzesłami, gustowne kanapy z
            fotelami, zegary, komody, artykuły mosiężne, lampy i wiele innych
            eleganckich przedmiotów.
          </p>
          <br />
          <p>
            Nasza siedziba znajduje się w miejscowości Mońki w województwie
            Podlaskim, położonej 40 km od Białegostoku. Nie prowadzimy sklepu
            stacjonarnego, lecz po wcześniejszej rozmowie telefonicznej można
            obejrzeć produkty w naszej siedzibie.
          </p>
        </StyledFooterColumn>
      </StyledFooter>
      <StyledFooter>
        <StyledFooterColumn>
          <p>&copy; Antyki Mońki 2021</p>
        </StyledFooterColumn>
      </StyledFooter>
    </Container>
  </StyledFooterWrapper>
)

export default Footer
