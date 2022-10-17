import React, { useEffect, useState } from 'react';
import { RouteComponentProps } from "react-router-dom";
import { withRouter } from 'react-router-dom';
import PageContentBlock from '@/components/elements/PageContentBlock';
import useFlash from '@/plugins/useFlash';
import tw from 'twin.macro';
import useSWR from 'swr';
import Spinner from '@/components/elements/Spinner';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import { Field as FormikField, Form, Formik, FormikHelpers } from 'formik';
import { Textarea } from '@/components/elements/Input';
import FormikFieldWrapper from '@/components/elements/FormikFieldWrapper';
import Button from '@/components/elements/Button';
import { number, object, string } from 'yup';
import Field from '@/components/elements/Field';
import Label from '@/components/elements/Label';
import Select from '@/components/elements/Select';
import GreyRowBox from '@/components/elements/GreyRowBox';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTicketAlt } from '@fortawesome/free-solid-svg-icons';
import styled from 'styled-components/macro';
import { Link } from 'react-router-dom';
import FlashMessageRender from '@/components/FlashMessageRender';

import getGameConfiguration from '@/api/cloudservers/getGameConfiguration';
import CreateGame from '@/api/cloudservers/CreateGame';


const Code = styled.code`${tw`font-mono py-1 px-2 bg-neutral-900 rounded text-sm inline-block`}`;

export interface GameConfigurationResponse {
    egg: any[];
    user: any[];
}

interface CreateValues {
    name: string;
    description: string;
    egg: string;
    memory: string;
    disk: string;
}

type Props = {
    id: string;
}

export default ({ match }: RouteComponentProps<Props>) => {

    var id = match.params.id;

    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const { data, error, mutate } = useSWR<GameConfigurationResponse>([ id, '/cloudservers' ], (id) => getGameConfiguration(id));

    const [ isSubmit, setSubmit ] = useState(false);

    useEffect(() => {
        if (!error) {
            clearFlashes('cloudservers');
        } else {
            clearAndAddHttpError({ key: 'cloudservers', error });
        }
    });

    const submit = ({ name, description, egg, memory, disk }: CreateValues, { setSubmitting }: FormikHelpers<CreateValues>) => {
        clearFlashes('cloudservers');
        clearFlashes('cloudservers:create');
        setSubmitting(false);
        setSubmit(true);

        console.log(name, description, egg, memory, disk);

        CreateGame(name, description, egg, memory, disk).then(() => {
            mutate();
            setSubmit(false);
        }).catch(error => {
            setSubmitting(false);
            setSubmit(false);
            clearAndAddHttpError({ key: 'cloudservers:create', error });
        });

    };



    return (
        <PageContentBlock title={'Create Game'} css={tw`flex flex-wrap`}>
            <div css={tw`w-full`}>
                <FlashMessageRender byKey={'cloudservers'} css={tw`mb-4`} />
            </div>
            <div css={tw`w-full`}>
                <FlashMessageRender byKey={'cloudservers:create'} css={tw`mb-4`} />
            </div>
            {!data ?
                <div css={tw`w-full`}>
                    <Spinner size={'large'} centered />
                </div>
                :
                <>
                    <div css={tw`w-full`}>
                        <Formik
                            onSubmit={submit}
                            initialValues={{ 
                                name: '', 
                                description: '', 
                                egg: data.egg[0]?.id,
                                memory: '1024', 
                                disk: '1024',
                            }}
                            validationSchema={object().shape({
                                name: string().required(),
                                description: string(),
                                egg: string().required(),
                                memory: string().required(),
                                disk: string().required(),
                            })}
                        >
                                
                            <Form>
                                <div css={tw`flex flex-wrap`}>
                                    <Field
                                        name={'egg'}
                                        type={'hidden'}
                                    />
                                    <div css={tw`lg:w-6/12 lg:pl-4 pt-4`}>
                                        <TitledGreyBox title={'Server Name'}>
                                            <div css={tw`px-1 py-2`}>
                                                <Field
                                                    name={'name'}
                                                    placeholder={'Server Name'}
                                                />
                                                <p css={tw`mt-1 text-xs text-neutral-400`}>Character limits: <code>a-z A-Z 0-9 _ - .</code> and <code>[Space]</code>.</p>
                                            </div>
                                        </TitledGreyBox>
                                    </div>
                                    <div css={tw`lg:w-6/12 lg:pl-4 pt-4`}>
                                        <TitledGreyBox title={'Server Description'}>
                                            <div css={tw`px-1 py-2`}>
                                                <Field
                                                    name={'description'}
                                                    placeholder={'Server Description'}
                                                    
                                                />
                                                <p css={tw`mt-1 text-xs text-neutral-400`}>A brief description of this server.</p>
                                            </div>
                                        </TitledGreyBox>
                                    </div>
                                    <div css={tw`lg:w-6/12 lg:pl-4 pt-4`}>
                                        <TitledGreyBox 
                                            title={
                                                <p css={tw`text-sm uppercase`}>
                                                    Server Memory (MB)
                                                    <span css={tw`bg-neutral-700 text-xs py-1 px-2 rounded-full mr-2 mb-1`} className="float-left">{data.user[0]?.memory} (MB) Left</span>
                                                </p>
                                            }
                                        >
                                            <div css={tw`px-1 py-2`}>
                                                <Field
                                                    name={'memory'}
                                                    placeholder={'Server Memory'}
                                                />
                                                <p css={tw`mt-1 text-xs text-neutral-400`}>The maximum amount of memory allowed for this container.</p>
                                            </div>
                                        </TitledGreyBox>
                                    </div>
                                    <div css={tw`lg:w-6/12 lg:pl-4 pt-4`}>
                                        <TitledGreyBox 
                                            title={
                                                <p css={tw`text-sm uppercase`}>
                                                    Server Disk (MB)
                                                    <span css={tw`bg-neutral-700 text-xs py-1 px-2 rounded-full mr-2 mb-1`} className="float-left">{data.user[0]?.disk} (MB) Left</span>
                                                </p>
                                            }
                                        >
                                            <div css={tw`px-1 py-2`}>
                                                <Field
                                                    name={'disk'}
                                                    placeholder={'Server Disk'}
                                                />
                                                <p css={tw`mt-1 text-xs text-neutral-400`}>This server will not be allowed to boot if it is using more than this amount of space.</p>
                                            </div>
                                        </TitledGreyBox>
                                    </div>
                                       
                                </div>
                                <br></br>
                                <div css={tw`flex justify-end text-right`}>
                                    <Button type={'submit'} disabled={isSubmit}>Create</Button>
                                </div>
                            </Form> 
                        </Formik>
                    </div>
                </>
            }
        </PageContentBlock>
    );
};
