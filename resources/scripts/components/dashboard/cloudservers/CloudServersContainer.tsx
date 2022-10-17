import React, { useEffect, useState } from 'react';
import PageContentBlock from '@/components/elements/PageContentBlock';
import tw from 'twin.macro';
import FlashMessageRender from '@/components/FlashMessageRender';
import Spinner from '@/components/elements/Spinner';
import useFlash from '@/plugins/useFlash';
import useSWR from 'swr';
import { Link } from 'react-router-dom';
import Button from '@/components/elements/Button';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import MessageBox from '@/components/MessageBox';

import getCloudServers from '@/api/cloudservers/getCloudServers';

export interface CloudServersResponse {
    eggs: any[];
}

export default () => {
    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const { data, error } = useSWR<CloudServersResponse>([ '/cloudservers' ], () => getCloudServers());


    useEffect(() => {
        if (!error) {
            clearFlashes('cloudservers');
        } else {
            clearAndAddHttpError({ key: 'cloudservers', error });
        }
    });

    return (
        <PageContentBlock title={'cloudservers'} css={tw`flex flex-wrap`}>
            <div css={tw`w-full`}>
                <FlashMessageRender byKey={'cloudservers'} css={tw`mb-4`} />
            </div>
            {!data ?
                <div css={tw`w-full`}>
                    <Spinner size={'large'} centered />
                </div>
                :
                <>
                    {data.eggs.length < 1 ?
                      <div css={tw`w-full`}>
                            <MessageBox type="info" title="Info">
                                There are no eggs.
                            </MessageBox>
                        </div>
                        :
                        (data.eggs.map((item, key) => (
                            <>
                            {item.status === 1 ?
                            <div css={tw`w-full lg:w-3/12 lg:pl-4`} key={key}>
                                <TitledGreyBox title={item.name}>
                                    <div css={tw`px-1 py-2`}>
                                        {item.img !== null ?
                                        <div css={tw`w-full pt-4`}>
                                            <img src={item.img} width={250} height={150} />
                                        </div>
                                        : null
                                        }
                                        <br></br>
                                        <div css={tw`flex justify-end text-center`}>
                                            <Link to={`/cloudservers/game/configuration/${item.id}`}>
                                                <Button size={'xsmall'} color={'primary'}>Create</Button>
                                            </Link>
                                        </div>
                                    </div>
                                </TitledGreyBox>
                                <br></br>
                            </div>
                            : null
                            }
                            </>
                        )))
                    }
                </>
            }
        </PageContentBlock>
    );
};
