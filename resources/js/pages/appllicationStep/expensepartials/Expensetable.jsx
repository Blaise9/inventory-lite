import { Icon } from '@iconify/react';
import { TablePagination, Tooltip, Zoom } from '@mui/material'
import React, { useState, useEffect } from 'react'
import Api from '../../../api/Api';
import { NavLink } from 'react-router-dom';
import { dateReformat, formatnumber } from '../../../api/Util';
import { motion } from 'framer-motion';
import Button from '../../../components/inputs/Button';
import { SlideUpAndDownAnimation } from '../../../api/Util';
import { AnimatePresence } from 'framer-motion';
import { SnackbarProvider, useSnackbar } from 'notistack'
import Loadingwheel from '../../../components/Loaders/Loadingwheel';
const Expensetable = ({ data, setData, isLoading, setIsLoading, setFilters, fetchExpenseData, setShowExpenseForm }) => {
    const { enqueueSnackbar, closeSnackbar } = useSnackbar()
    const [deleteExpenseByID, setDeleteExpenseByID] = useState(null)

    const handleChangePage = (event, newPage) => {
        if ((newPage + 1) > data.current_page) {
            fetchExpenseData(data.next_page_url)
        } else {
            fetchExpenseData(data.prev_page_url)
        }
    };

    const handleChangeRowsPerPage = (event) => {
        setRowsPerPage(+event.target.value);
    };

    const handleCategoryDelete = () => {
        Api.delete('/expense/delete/' + deleteExpenseByID)
            .then(res => {
                fetchExpenseData()
                setDeleteExpenseByID(null)
            })
            .catch(err => {
                enqueueSnackbar(`Failed: ${err?.response?.data?.message} `, { variant: 'error' })
                setDeleteExpenseByID(null)
            })
    };
    useEffect(() => {
        fetchExpenseData()
    }, [])

 



    return (
        <div className="flex flex-col w-full min-h-[36rem] h-max relative ">
            <div className="flex flex-col  overflow-hidden w-full">
                <div className="flex-auto p-0">
                    <div className="overflow-x-auto">
                        <table className="w-full overflow-hidden">
                            <thead className="bg-secondary-200 ">
                                <tr>
                                    <th className="px-6 py-3 text-left rtl:text-right  whitespace-nowrap font-semibold dark:text-secondary-600">
                                        #
                                    </th>

                                    <th className="px-6 py-3 text-left rtl:text-right  whitespace-nowrap font-semibold dark:text-secondary-600">
                                        Date modified
                                    </th>
                                    <th className="px-6 py-3 text-left rtl:text-right  whitespace-nowrap font-semibold dark:text-secondary-600">
                                        Item Name
                                    </th>
                                  
                                    <th className="px-6 py-3 text-left rtl:text-right  whitespace-nowrap font-semibold dark:text-secondary-600">
                                        Action
                                    </th>

                                </tr>
                            </thead>
                            <tbody className="divide-y divide-secondary-200 dark:divide-secondary-800">
                                {data?.data && data?.data.map((x, i) => {
                                    return (
                                        <tr
                                            key={i}
                                            className={`${i % 2 !== 0 && 'bg-secondary-100 dark:bg-dark-bg'
                                                }`}
                                        >
                                            <td className="px-6 py-2 !text-xs whitespace-nowrap">
                                                <div className="flex items-center">
                                                    <h6 className="mb-0 ">

                                                    </h6>
                                                </div>
                                            </td>
                                            <td className="px-6 py-2 !text-xs whitespace-nowrap">
                                                <div className="flex items-center">
                                                    <h6 className="mb-0  ">
                                                        {dateReformat(x.created_at)}
                                                    </h6>
                                                </div>
                                            </td>
                                            <td className="px-6 py-2 !text-xs whitespace-nowrap">
                                                <div className="flex items-center">
                                                    <h6 className="mb-0  ">
                                                        {x.name}
                                                    </h6>
                                                </div>
                                            </td>

                                            <td className="px-6 py-2 !text-xs flex items-center gap-2 whitespace-nowrap">
                                                <Tooltip title="Update Expense name" arrow TransitionComponent={Zoom}>
                                                    <span
                                                        onClick={() => setShowExpenseForm({
                                                            mode: 'Update Expense Data',
                                                            id: x.id,
                                                            name: x.name
                                                        })}
                                                        className=" p-1 rounded-full border border-gray-400/70 active:border-gray-400/40 text-blue-900   text-sm font-semibold leading-5  hover:cursor-pointer"
                                                    >
                                                        <Icon className='' icon="mdi:database-edit-outline" fontSize={20} />
                                                    </span>
                                                </Tooltip>
                                                <Tooltip title="Remove category" arrow TransitionComponent={Zoom}>
                                                    <button onClick={() => setDeleteExpenseByID(x.id)}
                                                        className=" p-1 rounded-full border border-gray-400/70 active:border-gray-400/40  text-red-900 text-sm font-semibold leading-5  hover:cursor-pointer"
                                                    >
                                                        <Icon className='' icon="ph:trash" fontSize={20} />
                                                    </button>
                                                </Tooltip>
                                            </td>
                                        </tr>
                                    )
                                })}
                            </tbody>
                        </table>
                        {isLoading && <Loadingwheel />
                        }
                    </div>
                </div>
            </div>
            <TablePagination className=" !mt-auto "
                rowsPerPageOptions={[10]}
                component="div"
                count={data?.total ?? 0}
                rowsPerPage={data?.per_page ?? 0}
                page={(data?.current_page - 1) ?? 0}
                onPageChange={handleChangePage}
                onRowsPerPageChange={handleChangeRowsPerPage}
            />
            <AnimatePresence>
                {deleteExpenseByID && <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    exit={{ opacity: 0 }}
                    className=' fixed inset-0 z-30 flex h-full items-end bg-black/30'>
                    <motion.div
                        variants={SlideUpAndDownAnimation}
                        initial='initial'
                        animate='animate'
                        exit='exit'
                        className=' max-w-2xl bg-white w-full mx-auto min-h-[18rem] rounded-t-md flex flex-col'>
                        <nav className='flex flex-col items-center justify-center grow'>
                            <nav className=' text-gray-400 p-2 bg-gray-100 rounded-full my-3'><Icon icon="ph:warning-circle-light" fontSize={70} /></nav>
                            <nav className=''>This action is irreversible</nav>
                            <nav className=' mt-2 text-sm'>Do want to continue? </nav>
                        </nav>
                        <nav className="mt-auto flex items-center w-full pb-1 px-2 gap-1 flex-col lg:flex-row">
                            <Button onClick={() => handleCategoryDelete()} otherClasses="w-full basis-[100%] lg:basis-[50%]" info text="Yes Remove" />
                            <Button onClick={() => setDeleteExpenseByID(null)} otherClasses="w-full basis-[100%] lg:basis-[50%]" neutral text="No Cancel" />
                        </nav>
                    </motion.div>
                </motion.div>}
            </AnimatePresence>
        </div>
    )
}

export default Expensetable
