import React from 'react';
import { NavLink, Route, RouteComponentProps, Switch } from 'react-router-dom';
import NavigationBar from '@/components/NavigationBar';
import NotFound from '@/components/screens/NotFound';
import TransitionRouter from '@/TransitionRouter';
import SubNavigation from '@/components/elements/SubNavigation';

import CloudServersContainer from '@/components/dashboard/cloudservers/CloudServersContainer';
import GameConfiguration from '@/components/dashboard/cloudservers/GameConfiguration';

export default ({ location }: RouteComponentProps) => (
    <>
        <NavigationBar/>
        {location.pathname.startsWith('/cloudservers') &&
        <SubNavigation>
            <div>
                <NavLink to={'/cloudservers'} exact>Cloud Servers</NavLink>
            </div>
        </SubNavigation>
        }
        <TransitionRouter>
            <Switch location={location}>
                <Route path={'/cloudservers'} component={CloudServersContainer} exact/>
                <Route path={`/cloudservers/game/configuration/:id`} component={GameConfiguration} exact/>
                <Route path={'*'} component={NotFound}/>
            </Switch>
        </TransitionRouter>
    </>
);
